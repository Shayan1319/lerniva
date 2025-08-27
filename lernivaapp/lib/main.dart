import 'package:flutter/material.dart';

// Import your pages
import 'pages/student_page.dart';
import 'pages/login_page.dart';
import 'pages/sign_up_page.dart';
import 'pages/splash_screen.dart';
import 'components/dashboard/teacher_dashboard.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatefulWidget {
  const MyApp({super.key});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  ThemeMode _themeMode = ThemeMode.system;
  bool _notificationsEnabled = true;

  void _toggleTheme(bool isDark) {
    setState(() {
      _themeMode = isDark ? ThemeMode.dark : ThemeMode.light;
    });
  }

  void _toggleNotifications(bool enabled) {
    setState(() {
      _notificationsEnabled = enabled;
    });
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Lurniva App',
      debugShowCheckedModeBanner: false,
      themeMode: _themeMode,
      theme: ThemeData(
        fontFamily: 'Roboto',
        brightness: Brightness.light,
        primarySwatch: Colors.indigo,
        scaffoldBackgroundColor: Colors.grey[100],
        appBarTheme: const AppBarTheme(
          backgroundColor: Color(0xFF3B38FF),
          foregroundColor: Colors.white,
          elevation: 0,
        ),
      ),
      darkTheme: ThemeData.dark(),

      // 🔹 Start with splash
      initialRoute: '/',

      routes: {
        '/': (context) => const SplashScreen(),

        '/login': (context) => const LoginPage(),

        '/signup': (context) => const SignUpPage(),

        '/teacher': (context) => const TeacherDashboard(
              teacherName: "John Doe",
              teacherDescription: "Mathematics Teacher",
              courseName: "Algebra & Geometry",
              classId: "10-A",
              profileImageUrl: "https://i.pravatar.cc/150?img=47",
            ),

        '/student': (context) => StudentPage(
              toggleTheme: _toggleTheme,
              themeMode: _themeMode,
              toggleNotifications: _toggleNotifications,
              notificationsEnabled: _notificationsEnabled,
            ),
      },
    );
  }
}
