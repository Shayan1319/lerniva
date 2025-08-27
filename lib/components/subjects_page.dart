import 'package:flutter/material.dart';

class SubjectsPage extends StatelessWidget {
  const SubjectsPage({super.key});

  final List<Map<String, dynamic>> subjects = const [
    {'title': 'Urdu', 'teacher': 'Ms. Ayesha', 'icon': Icons.menu_book},
    {'title': 'Islamiyat', 'teacher': 'Mr. Hamza', 'icon': Icons.mosque},
    {'title': 'Pakistan Studies', 'teacher': 'Mrs. Sara', 'icon': Icons.map},
    {'title': 'English', 'teacher': 'Mr. James', 'icon': Icons.language},
    {'title': 'Science', 'teacher': 'Ms. Fatima', 'icon': Icons.science},
    {'title': 'Physics', 'teacher': 'Dr. Noman', 'icon': Icons.bolt},
    {'title': 'Chemistry', 'teacher': 'Dr. Sana', 'icon': Icons.biotech},
    {'title': 'Mathematics', 'teacher': 'Mr. Khan', 'icon': Icons.calculate},
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF5F7FA),
      appBar: AppBar(
        elevation: 0,
        flexibleSpace: Container(
          decoration: const BoxDecoration(
            gradient: LinearGradient(
              colors: [Color(0xFF4facfe), Color(0xFF00f2fe)],
              begin: Alignment.topLeft,
              end: Alignment.bottomRight,
            ),
          ),
        ),
        title: const Text(
          "📚 Subjects",
          style: TextStyle(fontWeight: FontWeight.bold, letterSpacing: 1.2),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications, size: 26),
            onPressed: () {},
          ),
          const SizedBox(width: 8),
        ],
        shape: const RoundedRectangleBorder(
          borderRadius: BorderRadius.vertical(bottom: Radius.circular(20)),
        ),
      ),

      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // 🎭 Student Info Card
            AnimatedContainer(
              duration: const Duration(milliseconds: 800),
              curve: Curves.easeInOut,
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  colors: [
                    Colors.white.withOpacity(0.4),
                    Colors.white.withOpacity(0.1)
                  ],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                borderRadius: BorderRadius.circular(18),
                border: Border.all(color: Colors.white.withOpacity(0.4)),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.05),
                    blurRadius: 12,
                    offset: const Offset(0, 6),
                  ),
                ],
              ),
              child: Row(
                children: [
                  Hero(
                    tag: "student-avatar",
                    child: const CircleAvatar(
                      radius: 32,
                      backgroundImage: AssetImage("assets/images/student.png"),
                    ),
                  ),
                  const SizedBox(width: 16),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: const [
                      Text("John Doe",
                          style: TextStyle(
                              fontSize: 18,
                              fontWeight: FontWeight.bold,
                              color: Colors.black87)),
                      SizedBox(height: 4),
                      Text("Roll No: 12345",
                          style: TextStyle(color: Colors.black54)),
                      Text("Class: 10th Grade • Section A",
                          style: TextStyle(color: Colors.black54)),
                    ],
                  ),
                ],
              ),
            ),

            const SizedBox(height: 20),

            // 📘 Subjects Grid
            Expanded(
              child: GridView.builder(
                itemCount: subjects.length,
                gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                  crossAxisCount: 2,
                  mainAxisSpacing: 14,
                  crossAxisSpacing: 14,
                  childAspectRatio: 1,
                ),
                itemBuilder: (context, index) {
                  final subject = subjects[index];
                  final colors = [
                    [Colors.purple.shade400, Colors.purple.shade200],
                    [Colors.blue.shade400, Colors.blue.shade200],
                    [Colors.orange.shade400, Colors.orange.shade200],
                    [Colors.green.shade400, Colors.green.shade200],
                    [Colors.red.shade400, Colors.red.shade200],
                    [Colors.teal.shade400, Colors.teal.shade200],
                    [Colors.indigo.shade400, Colors.indigo.shade200],
                    [Colors.cyan.shade400, Colors.cyan.shade200],
                  ];

                  return TweenAnimationBuilder<double>(
                    duration: Duration(milliseconds: 500 + index * 120),
                    curve: Curves.easeOutBack,
                    tween: Tween(begin: 0, end: 1),
                    builder: (context, value, child) {
                      return Transform.scale(
                        scale: value,
                        child: GestureDetector(
                          onTap: () {
                            Navigator.push(
                              context,
                              PageRouteBuilder(
                                transitionDuration:
                                    const Duration(milliseconds: 700),
                                pageBuilder: (_, __, ___) =>
                                    SubjectDetailsPage(subject: subject),
                                transitionsBuilder: (_, anim, __, child) {
                                  return FadeTransition(
                                    opacity: anim,
                                    child: ScaleTransition(
                                      scale: Tween(begin: 0.9, end: 1.0)
                                          .animate(anim),
                                      child: child,
                                    ),
                                  );
                                },
                              ),
                            );
                          },
                          child: AnimatedContainer(
                            duration: const Duration(milliseconds: 300),
                            decoration: BoxDecoration(
                              gradient: LinearGradient(
                                colors: colors[index % colors.length],
                                begin: Alignment.topLeft,
                                end: Alignment.bottomRight,
                              ),
                              borderRadius: BorderRadius.circular(18),
                              boxShadow: [
                                BoxShadow(
                                  color: colors[index % colors.length][0]
                                      .withOpacity(0.4),
                                  blurRadius: 10,
                                  offset: const Offset(0, 6),
                                ),
                              ],
                            ),
                            child: Column(
                              mainAxisAlignment: MainAxisAlignment.center,
                              children: [
                                Icon(
                                  subject['icon'] as IconData,
                                  size: 60,
                                  color: Colors.white,
                                ),
                                const SizedBox(height: 12),
                                Text(
                                  subject['title']!,
                                  style: const TextStyle(
                                    fontSize: 16,
                                    fontWeight: FontWeight.bold,
                                    color: Colors.white,
                                  ),
                                ),
                                const SizedBox(height: 6),
                                Text(
                                  subject['teacher']!,
                                  style: const TextStyle(
                                    fontSize: 13,
                                    color: Colors.white70,
                                  ),
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}

// 📖 Subject Details Page (with Icon instead of Lottie)
class SubjectDetailsPage extends StatelessWidget {
  final Map<String, dynamic> subject;
  const SubjectDetailsPage({super.key, required this.subject});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.white,
      appBar: AppBar(
        backgroundColor: Colors.blueAccent,
        title: Text(subject['title']!),
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              subject['icon'] as IconData,
              size: 150,
              color: Colors.blueAccent,
            ),
            const SizedBox(height: 20),
            Text(
              "Welcome to ${subject['title']} class!",
              style: const TextStyle(
                  fontSize: 20, fontWeight: FontWeight.bold, color: Colors.black87),
            ),
            const SizedBox(height: 10),
            Text(
              "Instructor: ${subject['teacher']!}",
              style: const TextStyle(fontSize: 16, color: Colors.black54),
            ),
          ],
        ),
      ),
    );
  }
}
