import 'package:flutter/material.dart';
import 'package:file_picker/file_picker.dart';

class AssignmentLessonPage extends StatefulWidget {
  const AssignmentLessonPage({super.key});

  @override
  State<AssignmentLessonPage> createState() => _AssignmentLessonPageState();
}

class _AssignmentLessonPageState extends State<AssignmentLessonPage> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _titleController = TextEditingController();
  final TextEditingController _descController = TextEditingController();
  PlatformFile? pickedFile;
  DateTime? _dueDate;

  List<Map<String, dynamic>> tasks = [];

  // Pick file
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

  // Pick deadline
  Future<void> _pickDeadline() async {
    DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now().add(const Duration(days: 1)),
      firstDate: DateTime.now(),
      lastDate: DateTime(2100),
    );
    if (picked != null) {
      setState(() => _dueDate = picked);
    }
  }

  // Save task
  void _saveTask() {
    if (_formKey.currentState!.validate()) {
      if (pickedFile == null) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Please attach a lesson file")),
        );
        return;
      }

      setState(() {
        tasks.add({
          "title": _titleController.text,
          "desc": _descController.text,
          "deadline": _dueDate,
          "file": pickedFile,
        });
        _titleController.clear();
        _descController.clear();
        pickedFile = null;
        _dueDate = null;
      });

      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("✅ Task added successfully!")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Assignments & Lesson Plans"),
        backgroundColor: Colors.blue,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Upload Lesson Plan
            const Text(
              "Upload Weekly Lesson Plan",
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            OutlinedButton.icon(
              onPressed: _pickFile,
              icon: const Icon(Icons.upload_file),
              label: Text(
                pickedFile == null
                    ? "Upload PDF / DOC"
                    : "Selected: ${pickedFile!.name}",
              ),
            ),
            const SizedBox(height: 30),

            // Task Form
            const Text(
              "Prepare Task for Students",
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            Card(
              elevation: 4,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12)),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Form(
                  key: _formKey,
                  child: Column(
                    children: [
                      TextFormField(
                        controller: _titleController,
                        decoration: const InputDecoration(
                          labelText: "Task Title",
                          border: OutlineInputBorder(),
                        ),
                        validator: (value) =>
                            value!.isEmpty ? "Enter task title" : null,
                      ),
                      const SizedBox(height: 10),
                      TextFormField(
                        controller: _descController,
                        maxLines: 3,
                        decoration: const InputDecoration(
                          labelText: "Task Description",
                          border: OutlineInputBorder(),
                        ),
                        validator: (value) =>
                            value!.isEmpty ? "Enter task description" : null,
                      ),
                      const SizedBox(height: 10),
                      Row(
                        children: [
                          Expanded(
                            child: Text(_dueDate == null
                                ? "No deadline selected"
                                : "Deadline: ${_dueDate!.day}/${_dueDate!.month}/${_dueDate!.year}"),
                          ),
                          TextButton.icon(
                            onPressed: _pickDeadline,
                            icon: const Icon(Icons.date_range),
                            label: const Text("Pick Deadline"),
                          ),
                        ],
                      ),
                      const SizedBox(height: 15),
                      SizedBox(
                        width: double.infinity,
                        child: ElevatedButton(
                          onPressed: _saveTask,
                          child: const Text("Save Task"),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),

            const SizedBox(height: 30),

            // Task List
            const Text(
              "Assignments & Deadlines",
              style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
            ),
            const Divider(),
            tasks.isEmpty
                ? const Text("No tasks added yet.")
                : ListView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: tasks.length,
                    itemBuilder: (context, index) {
                      final task = tasks[index];
                      return Card(
                        elevation: 2,
                        margin: const EdgeInsets.symmetric(vertical: 6),
                        shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(12)),
                        child: ListTile(
                          title: Text(task["title"]),
                          subtitle: Text(
                            "${task["desc"]}\nDeadline: ${task["deadline"] != null ? "${task["deadline"].day}/${task["deadline"].month}/${task["deadline"].year}" : "No deadline"}\nFile: ${task["file"] != null ? task["file"].name : "No file"}",
                          ),
                          isThreeLine: true,
                          trailing: IconButton(
                            icon: const Icon(Icons.delete, color: Colors.red),
                            onPressed: () {
                              setState(() => tasks.removeAt(index));
                            },
                          ),
                        ),
                      );
                    },
                  ),
          ],
        ),
      ),
    );
  }
}
