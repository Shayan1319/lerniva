import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';

class TeacherTaskPage extends StatefulWidget {
  const TeacherTaskPage({super.key});

  @override
  State<TeacherTaskPage> createState() => _TeacherTaskPageState();
}

class _TeacherTaskPageState extends State<TeacherTaskPage> {
  final TextEditingController taskTitleController = TextEditingController();
  final TextEditingController taskDescriptionController =
      TextEditingController();
  PlatformFile? pickedFile;

  // Pick a file (PDF, DOC, DOCX)
  Future<void> _pickFile() async {
    FilePickerResult? result = await FilePicker.platform.pickFiles(
      type: FileType.custom,
      allowedExtensions: ['pdf', 'doc', 'docx'],
    );

    if (result != null) {
      setState(() {
        pickedFile = result.files.first;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("📂 File Selected: ${pickedFile!.name}")),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("No file selected")),
      );
    }
  }

  // Upload Task
  void _uploadTask() {
    if (taskTitleController.text.isEmpty ||
        taskDescriptionController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Please fill in all fields")),
      );
      return;
    }

    if (pickedFile == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Please attach a task file")),
      );
      return;
    }

    // TODO: Upload task to backend or Firebase
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(
          content:
              Text("✅ Task '${taskTitleController.text}' uploaded successfully!")),
    );

    // Clear form
    setState(() {
      taskTitleController.clear();
      taskDescriptionController.clear();
      pickedFile = null;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Prepare Task for Students"),
        backgroundColor: Colors.blue,
        foregroundColor: Colors.white,
        elevation: 4,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Card(
          elevation: 6,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(15),
          ),
          child: Padding(
            padding: const EdgeInsets.all(20),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Task Title
                TextField(
                  controller: taskTitleController,
                  decoration: const InputDecoration(
                    labelText: "Task Title",
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 20),

                // Task Description
                TextField(
                  controller: taskDescriptionController,
                  maxLines: 5,
                  decoration: const InputDecoration(
                    labelText: "Task Description",
                    border: OutlineInputBorder(),
                  ),
                ),
                const SizedBox(height: 20),

                // File Upload
                OutlinedButton.icon(
                  onPressed: _pickFile,
                  icon: const Icon(Icons.upload_file),
                  label: const Text("Attach Task File"),
                ),
                if (pickedFile != null) ...[
                  const SizedBox(height: 10),
                  Text(
                    "Selected File: ${pickedFile!.name}",
                    style: const TextStyle(
                        fontStyle: FontStyle.italic, color: Colors.black87),
                  ),
                ],

                const SizedBox(height: 30),

                // Submit Button
                SizedBox(
                  width: double.infinity,
                  child: ElevatedButton(
                    onPressed: _uploadTask,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.blue,
                      foregroundColor: Colors.white,
                      padding: const EdgeInsets.symmetric(vertical: 15),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(10),
                      ),
                    ),
                    child: const Text("Submit Task"),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
