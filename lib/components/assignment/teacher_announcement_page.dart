import 'package:flutter/material.dart';

class TeacherAnnouncementPage extends StatelessWidget {
  const TeacherAnnouncementPage({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Principal Notices"),
        backgroundColor: Colors.deepPurple,
      ),
      body: const Center(
        child: Text("Principal notices will appear here."),
      ),
    );
  }
}
