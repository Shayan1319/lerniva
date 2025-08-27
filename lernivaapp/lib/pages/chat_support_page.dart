import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';

class ChatSupportPage extends StatefulWidget {
  const ChatSupportPage({super.key});

  @override
  State<ChatSupportPage> createState() => _ChatSupportPageState();
}

class _ChatSupportPageState extends State<ChatSupportPage> {
  final TextEditingController _messageController = TextEditingController();
  final ScrollController _scrollController = ScrollController();
  final List<_Message> _messages = [];

  bool _isTyping = false;
  File? _image;

  void _sendMessage(String content, {bool isUser = true, File? image}) {
    if (content.trim().isEmpty && image == null) return;

    setState(() {
      _messages.add(_Message(
        content: content.trim(),
        isUser: isUser,
        timestamp: DateTime.now(),
        image: image,
      ));
      _isTyping = true;
    });

    _scrollToBottom();

    if (isUser) {
      Future.delayed(const Duration(seconds: 2), () {
        setState(() {
          _messages.add(_Message(
            content: "Thank you for contacting Lurniva support! How can we assist you today?",
            isUser: false,
            timestamp: DateTime.now(),
          ));
          _isTyping = false;
        });
        _scrollToBottom();
      });
    }

    _messageController.clear();
  }

  void _scrollToBottom() {
    Future.delayed(const Duration(milliseconds: 300), () {
      _scrollController.animateTo(
        _scrollController.position.maxScrollExtent + 60,
        duration: const Duration(milliseconds: 300),
        curve: Curves.easeOut,
      );
    });
  }

  Future<void> _pickImage() async {
    final picked = await ImagePicker().pickImage(source: ImageSource.gallery);
    if (picked != null) {
      _sendMessage('', image: File(picked.path));
    }
  }

  static const Color lurnivaBlue = Color(0xFF3B38FF);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Chat Support"),
        backgroundColor: lurnivaBlue,
        centerTitle: true,
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(12),
              controller: _scrollController,
              itemCount: _messages.length + (_isTyping ? 1 : 0),
              itemBuilder: (context, index) {
                if (_isTyping && index == _messages.length) {
                  return const Padding(
                    padding: EdgeInsets.symmetric(vertical: 8),
                    child: Row(
                      children: [
                        CircleAvatar(child: Icon(Icons.support_agent)),
                        SizedBox(width: 10),
                        Text("Typing...", style: TextStyle(fontStyle: FontStyle.italic)),
                      ],
                    ),
                  );
                }

                final msg = _messages[index];
                return Align(
                  alignment: msg.isUser ? Alignment.centerRight : Alignment.centerLeft,
                  child: Container(
                    margin: const EdgeInsets.symmetric(vertical: 6),
                    padding: const EdgeInsets.all(12),
                    constraints: const BoxConstraints(maxWidth: 280),
                    decoration: BoxDecoration(
                      color: msg.isUser ? lurnivaBlue : Colors.grey[200],
                      borderRadius: BorderRadius.circular(12),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        if (msg.image != null)
                          ClipRRect(
                            borderRadius: BorderRadius.circular(8),
                            child: Image.file(msg.image!, width: 200),
                          ),
                        if (msg.content.isNotEmpty)
                          Text(
                            msg.content,
                            style: TextStyle(
                              color: msg.isUser ? Colors.white : Colors.black87,
                            ),
                          ),
                        const SizedBox(height: 4),
                        Text(
                          _formatTime(msg.timestamp),
                          style: TextStyle(
                            fontSize: 10,
                            color: msg.isUser ? Colors.white70 : Colors.grey,
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          const Divider(height: 1),
          Container(
            color: Colors.grey[100],
            padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 8),
            child: Row(
              children: [
                IconButton(
                  icon: const Icon(Icons.image, color: lurnivaBlue),
                  onPressed: _pickImage,
                ),
                Expanded(
                  child: TextField(
                    controller: _messageController,
                    decoration: const InputDecoration(
                      hintText: "Type your message...",
                      border: InputBorder.none,
                    ),
                    onSubmitted: (text) => _sendMessage(text),
                  ),
                ),
                IconButton(
                  icon: const Icon(Icons.send, color: lurnivaBlue),
                  onPressed: () => _sendMessage(_messageController.text),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  String _formatTime(DateTime time) {
    return "${time.hour.toString().padLeft(2, '0')}:${time.minute.toString().padLeft(2, '0')}";
  }
}

class _Message {
  final String content;
  final bool isUser;
  final DateTime timestamp;
  final File? image;

  _Message({
    required this.content,
    required this.isUser,
    required this.timestamp,
    this.image,
  });
}
