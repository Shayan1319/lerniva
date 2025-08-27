import 'package:flutter/material.dart';

class NotificationsPage extends StatefulWidget {
  const NotificationsPage({super.key});

  @override
  State<NotificationsPage> createState() => _NotificationsPageState();
}

class _NotificationsPageState extends State<NotificationsPage> {
  List<Map<String, dynamic>> notifications = [
    {
      'title': 'New Assignment',
      'subtitle': 'Science homework is due tomorrow.',
      'icon': Icons.assignment,
      'time': '2h ago',
      'selected': false,
    },
    {
      'title': 'Attendance Alert',
      'subtitle': 'You missed your English class.',
      'icon': Icons.warning_amber_rounded,
      'time': '5h ago',
      'selected': false,
    },
    {
      'title': 'New Message',
      'subtitle': 'Teacher sent you a message.',
      'icon': Icons.chat_bubble,
      'time': '1d ago',
      'selected': false,
    },
    {
      'title': 'Fee Due',
      'subtitle': 'Monthly fee is due on 15th July.',
      'icon': Icons.credit_card,
      'time': '3d ago',
      'selected': false,
    },
  ];

  bool get isSelectionMode => notifications.any((n) => n['selected'] == true);
  bool get allSelected => notifications.every((n) => n['selected'] == true);

  void toggleAll(bool value) {
    setState(() {
      for (var n in notifications) {
        n['selected'] = value;
      }
    });
  }

  void deleteSelected() {
    setState(() {
      notifications.removeWhere((n) => n['selected']);
    });
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Deleted selected notifications")),
    );
  }

  void archiveSelected() {
    setState(() {
      for (var n in notifications) {
        if (n['selected']) n['subtitle'] += ' (Archived)';
        n['selected'] = false;
      }
    });
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Archived selected notifications")),
    );
  }

  void cancelSelection() {
    setState(() {
      for (var n in notifications) {
        n['selected'] = false;
      }
    });
  }

  void showNotificationDetails(Map<String, dynamic> n) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: Text(n['title']),
        content: Text(n['subtitle']),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text("Close"),
          ),
        ],
      ),
    );
  }

  static const primaryColor = Color(0xFF3B38FF);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: primaryColor,
        title: Text(isSelectionMode ? 'Select Notifications' : 'Notifications'),
        actions: [
          if (isSelectionMode) ...[
            IconButton(
              icon: const Icon(Icons.delete),
              tooltip: 'Delete',
              onPressed: deleteSelected,
            ),
            IconButton(
              icon: const Icon(Icons.archive),
              tooltip: 'Archive',
              onPressed: archiveSelected,
            ),
            IconButton(
              icon: Icon(allSelected ? Icons.remove_done : Icons.done_all),
              tooltip: allSelected ? 'Unselect All' : 'Select All',
              onPressed: () => toggleAll(!allSelected),
            ),
            IconButton(
              icon: const Icon(Icons.close),
              tooltip: 'Cancel',
              onPressed: cancelSelection,
            ),
          ] else
            TextButton(
              onPressed: () => ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text("All marked as read.")),
              ),
              child: const Text("Mark all read", style: TextStyle(color: Colors.white)),
            ),
        ],
      ),
      body: notifications.isEmpty
          ? const Center(child: Text("No notifications"))
          : ListView.builder(
              itemCount: notifications.length,
              padding: const EdgeInsets.all(12),
              itemBuilder: (context, index) {
                final n = notifications[index];
                return GestureDetector(
                  onTap: () {
                    if (isSelectionMode) {
                      setState(() => n['selected'] = !n['selected']);
                    } else {
                      showNotificationDetails(n);
                    }
                  },
                  onLongPress: () => setState(() => n['selected'] = true),
                  child: Card(
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    elevation: 2,
                    child: CheckboxListTile(
                      value: n['selected'],
                      onChanged: (val) => setState(() => n['selected'] = val!),
                      controlAffinity: ListTileControlAffinity.leading,
                      contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                      title: Row(
                        children: [
                          CircleAvatar(
                            radius: 20,
                            backgroundColor: Colors.indigo.shade50,
                            child: Icon(n['icon'], color: primaryColor, size: 20),
                          ),
                          const SizedBox(width: 12),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(n['title'], style: const TextStyle(fontWeight: FontWeight.bold)),
                                const SizedBox(height: 4),
                                Text(n['subtitle'], style: const TextStyle(fontSize: 13, color: Colors.black87)),
                                const SizedBox(height: 4),
                                Text(n['time'], style: const TextStyle(fontSize: 12, color: Colors.grey)),
                              ],
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                );
              },
            ),
    );
  }
}
