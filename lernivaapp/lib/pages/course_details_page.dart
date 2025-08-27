import 'package:flutter/material.dart';

class CourseDetailsPage extends StatelessWidget {
  final Map<String, dynamic> course;

  const CourseDetailsPage({super.key, required this.course});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(course['title'] ?? 'Course Details'),
        backgroundColor: const Color(0xFF3B38FF), // Optional: matching theme
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              course['title'] ?? '',
              style: const TextStyle(
                fontSize: 24,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(height: 12),
            Text(
              'Instructor: ${course['instructor']}',
              style: const TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 8),
            Text(
              'Duration: ${course['duration']}',
              style: const TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 8),
            Text(
              'Price: ${course['price']}',
              style: const TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 8),
            Text(
              'Rating: ${course['rating']} ⭐',
              style: const TextStyle(fontSize: 16),
            ),
            const SizedBox(height: 16),
            const Text(
              'Course Description',
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            Text(
              course['description'] ?? '',
              style: const TextStyle(fontSize: 15),
            ),
          ],
        ),
      ),
    );
  }
}
