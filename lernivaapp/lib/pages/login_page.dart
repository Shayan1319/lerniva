import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

import 'sign_up_page.dart';
import 'student_page.dart';
import '../components/dashboard/teacher_dashboard.dart'; // ✅ Correct import

class LoginPage extends StatefulWidget {
  const LoginPage({super.key});

  @override
  State<LoginPage> createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _formKey = GlobalKey<FormState>();
  String email = '';
  String password = '';
  bool obscurePassword = true;
  bool isLoading = false;

  Future<void> _login() async {
    if (!_formKey.currentState!.validate()) return;
    _formKey.currentState!.save();

    setState(() => isLoading = true);

    try {
      final response = await http.post(
        Uri.parse("https://bm-boundaries-trunk-printed.trycloudflare.com/Lurniva/lerniva/api/login_api.php"),
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: {
          "email": email,
          "password": password,
        },
      );

      print("🔹 Sending Email: $email, Password: $password");
      print("🔹 Status Code: ${response.statusCode}");
      print("🔹 Response Body: ${response.body}");

      final data = jsonDecode(response.body);

      if (data["status"] == "success") {
        String type = data["type"]; // "student" or "teacher"

        if (type == "student") {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (context) => StudentPage(
                toggleTheme: (_) {},
                themeMode: ThemeMode.light,
                toggleNotifications: (_) {},
                notificationsEnabled: true,
              ),
            ),
          );
        } else if (type == "teacher") {
          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (context) => TeacherDashboard(   // ✅ Fixed
                teacherName: data['full_name'] ?? 'Teacher',
                teacherDescription: "Welcome back!",
                courseName: "Mathematics",   // replace with API value if available
                classId: "T-${data['id']}", // or actual ID from API
                profileImageUrl: "https://your-server.com/uploads/${data['photo'] ?? 'default.png'}",
              ),
            ),
          );
        } else {
          _showError("Unknown user type");
        }
      } else {
        _showError(data["message"] ?? "Login failed");
      }
    } catch (e) {
      _showError("Error: $e");
    } finally {
      setState(() => isLoading = false);
    }
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text(message)),
    );
  }

  @override
  Widget build(BuildContext context) {
    final size = MediaQuery.of(context).size;

    return Scaffold(
      backgroundColor: Colors.transparent,
      body: Container(
        width: size.width,
        height: size.height,
        decoration: const BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topLeft,
            end: Alignment.bottomRight,
            colors: [
              Color(0xFF1DA1F2),
              Color(0xFF794BC4),
              Color(0xFF17C3B2),
            ],
          ),
        ),
        child: Stack(
          children: [
            // Header
            Positioned(
              top: 0,
              left: 0,
              right: 0,
              child: ClipPath(
                clipper: OvalBottomClipper(),
                child: Container(
                  height: size.height * 0.5,
                  color: Colors.white,
                  child: Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Image.asset('assets/images/logo_dark.png', height: 80, width: 80),
                        const SizedBox(height: 10),
                        const GradientText(
                          'Lurniva',
                          style: TextStyle(fontSize: 30, fontWeight: FontWeight.bold),
                          gradient: LinearGradient(colors: [
                            Color(0xFF3B38FF),
                            Color(0xFF4C9AFF),
                            Color(0xFF00CFC1),
                          ]),
                        ),
                      ],
                    ),
                  ),
                ),
              ),
            ),

            // Form
            Align(
              alignment: Alignment.bottomCenter,
              child: SingleChildScrollView(
                padding: const EdgeInsets.fromLTRB(24, 0, 24, 32),
                child: Column(
                  children: [
                    SizedBox(height: size.height * 0.45 + 20),
                    const Text('Welcome Back',
                        style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white)),
                    const SizedBox(height: 6),
                    const Text('Log in to your Lurniva account', style: TextStyle(color: Colors.white70)),
                    const SizedBox(height: 24),

                    Form(
                      key: _formKey,
                      child: Column(
                        children: [
                          TextFormField(
                            decoration: _inputDecoration('Email or Username', Icons.email_outlined),
                            validator: (value) => value == null || value.isEmpty ? 'Please enter your email/username' : null,
                            onSaved: (value) => email = value!.trim(),
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            obscureText: obscurePassword,
                            decoration: _inputDecoration('Password', Icons.lock_outline).copyWith(
                              suffixIcon: IconButton(
                                icon: Icon(obscurePassword ? Icons.visibility_off : Icons.visibility),
                                onPressed: () => setState(() => obscurePassword = !obscurePassword),
                              ),
                            ),
                            validator: (value) => value == null || value.isEmpty ? 'Please enter your password' : null,
                            onSaved: (value) => password = value!.trim(),
                          ),

                          const SizedBox(height: 20),
                          SizedBox(
                            width: double.infinity,
                            height: 48,
                            child: ElevatedButton(
                              onPressed: isLoading ? null : _login,
                              style: ElevatedButton.styleFrom(
                                backgroundColor: Colors.transparent,
                                shadowColor: Colors.transparent,
                                padding: EdgeInsets.zero,
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                              ),
                              child: Ink(
                                decoration: const BoxDecoration(
                                  gradient: LinearGradient(colors: [Color(0xFF3B38FF), Color(0xFF00CFC1)]),
                                  borderRadius: BorderRadius.all(Radius.circular(12)),
                                ),
                                child: Container(
                                  alignment: Alignment.center,
                                  child: isLoading
                                      ? const CircularProgressIndicator(color: Colors.white)
                                      : const Text('Login', style: TextStyle(fontSize: 16, color: Colors.white)),
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(height: 12),

                          Align(
                            alignment: Alignment.centerRight,
                            child: TextButton(
                              onPressed: () {},
                              child: const Text('Forgot your password?', style: TextStyle(color: Colors.white)),
                            ),
                          ),
                          const SizedBox(height: 12),
                          Row(
                            children: const [
                              Expanded(child: Divider(color: Colors.white)),
                              Padding(
                                padding: EdgeInsets.symmetric(horizontal: 10),
                                child: Text("OR", style: TextStyle(color: Colors.white)),
                              ),
                              Expanded(child: Divider(color: Colors.white)),
                            ],
                          ),
                          const SizedBox(height: 12),

                          // Social login (optional)
                          Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              ElevatedButton.icon(
                                icon: const Icon(Icons.g_mobiledata, size: 28, color: Colors.white),
                                label: const Text('Google'),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: const Color(0xFF4285F4),
                                  foregroundColor: Colors.white,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                                ),
                                onPressed: () {},
                              ),
                              const SizedBox(width: 16),
                              ElevatedButton.icon(
                                icon: const Icon(Icons.account_circle, color: Colors.white),
                                label: const Text('Microsoft'),
                                style: ElevatedButton.styleFrom(
                                  backgroundColor: Colors.indigo,
                                  foregroundColor: Colors.white,
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                                ),
                                onPressed: () {},
                              ),
                            ],
                          ),
                          const SizedBox(height: 20),
                          TextButton(
                            onPressed: () => Navigator.push(
                              context,
                              MaterialPageRoute(builder: (context) => const SignUpPage()),
                            ),
                            child: const Text(
                              "Don't have an account? Sign Up",
                              style: TextStyle(color: Colors.white),
                            ),
                          ),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  InputDecoration _inputDecoration(String hint, IconData icon) {
    return InputDecoration(
      hintText: hint,
      prefixIcon: Icon(icon),
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
      filled: true,
      fillColor: Colors.white,
    );
  }
}

// GradientText Widget
class GradientText extends StatelessWidget {
  final String text;
  final TextStyle style;
  final Gradient gradient;

  const GradientText(this.text, {super.key, required this.style, required this.gradient});

  @override
  Widget build(BuildContext context) {
    return ShaderMask(
      shaderCallback: (bounds) => gradient.createShader(Offset.zero & bounds.size),
      child: Text(text, style: style.copyWith(color: Colors.white)),
    );
  }
}

// Oval Bottom Clipper
class OvalBottomClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    final path = Path();
    path.lineTo(0, size.height - 60);
    path.quadraticBezierTo(size.width / 2, size.height, size.width, size.height - 60);
    path.lineTo(size.width, 0);
    path.close();
    return path;
  }

  @override
  bool shouldReclip(CustomClipper<Path> oldClipper) => false;
}
