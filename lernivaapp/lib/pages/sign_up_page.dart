import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import 'login_page.dart';
import 'student_page.dart';
import '../components/dashboard/teacher_dashboard.dart';

class SignUpPage extends StatefulWidget {
  const SignUpPage({super.key});

  @override
  State<SignUpPage> createState() => _SignUpPageState();
}

class _SignUpPageState extends State<SignUpPage> {
  final _formKey = GlobalKey<FormState>();
  String name = '';
  String email = '';
  String password = '';
  bool obscurePassword = true;
  bool isLoading = false;

  Future<void> _handleSignup() async {
    setState(() => isLoading = true);

    final url = Uri.parse("http://192.168.1.8:8000/api/signup/");
    final body = {
      "name": name,
      "email": email,
      "password": password,
    };

    try {
      final response = await http.post(
        url,
        headers: {"Content-Type": "application/json"},
        body: jsonEncode(body),
      );

      if (response.statusCode == 201 || response.statusCode == 200) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Signup successful")),
        );

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
      } else {
        final error = jsonDecode(response.body);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Signup failed: ${error['error'] ?? 'Unknown error'}")),
        );
      }
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error: $e")),
      );
    } finally {
      setState(() => isLoading = false);
    }
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
            Align(
              alignment: Alignment.bottomCenter,
              child: SingleChildScrollView(
                padding: const EdgeInsets.fromLTRB(24, 0, 24, 32),
                child: Column(
                  children: [
                    SizedBox(height: size.height * 0.45 + 20),
                    const Text(
                      'Create an Account',
                      style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold, color: Colors.white),
                    ),
                    const SizedBox(height: 6),
                    const Text('Join the Lurniva platform', style: TextStyle(color: Colors.white70)),
                    const SizedBox(height: 24),
                    Form(
                      key: _formKey,
                      child: Column(
                        children: [
                          TextFormField(
                            decoration: _inputDecoration('Full Name', Icons.person_outline),
                            validator: (value) => value == null || value.isEmpty ? 'Enter your name' : null,
                            onSaved: (value) => name = value!,
                          ),
                          const SizedBox(height: 16),
                          TextFormField(
                            decoration: _inputDecoration('Email address', Icons.email_outlined),
                            validator: (value) => value == null || value.isEmpty ? 'Enter your email' : null,
                            onSaved: (value) => email = value!,
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
                            validator: (value) => value == null || value.isEmpty ? 'Enter your password' : null,
                            onSaved: (value) => password = value!,
                          ),
                          const SizedBox(height: 20),
                          SizedBox(
                            width: double.infinity,
                            height: 48,
                            child: ElevatedButton(
                              onPressed: () {
                                if (_formKey.currentState!.validate()) {
                                  _formKey.currentState!.save();
                                  _handleSignup();
                                }
                              },
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
                                      : const Text('Sign Up', style: TextStyle(fontSize: 16, color: Colors.white)),
                                ),
                              ),
                            ),
                          ),
                          const SizedBox(height: 16),
                          TextButton(
                            onPressed: () => Navigator.pop(context),
                            child: const Text(
                              'Already have an account? Log In',
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

// Oval Background Shape
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
