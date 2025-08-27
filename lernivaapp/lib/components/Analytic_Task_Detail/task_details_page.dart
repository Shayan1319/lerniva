// lib/pages/task_details_page.dart
import 'package:flutter/material.dart';

class TaskDetailsPage extends StatelessWidget {
  final String? subject;

  const TaskDetailsPage({super.key, this.subject});

  @override
  Widget build(BuildContext context) {
    final safeSubject = subject ?? "Unknown";

    return Scaffold(
      appBar: AppBar(title: Text("Task Details - $safeSubject")),
      body: Center(
        child: Text(
          "Details for subject: $safeSubject",
          style: const TextStyle(fontSize: 18),
        ),
      ),
    );
  }
}
