import 'package:flutter/material.dart';

class FeedbackPage extends StatefulWidget {
  const FeedbackPage({super.key});

  @override
  State<FeedbackPage> createState() => _FeedbackPageState();
}

class _FeedbackPageState extends State<FeedbackPage> {
  final _formKey = GlobalKey<FormState>();
  String name = '';
  String email = '';
  String message = '';
  bool isSubmitting = false;

  void _submitForm() {
    if (_formKey.currentState!.validate()) {
      setState(() => isSubmitting = true);

      Future.delayed(const Duration(seconds: 2), () {
        setState(() => isSubmitting = false);
        _formKey.currentState!.reset();

        showDialog(
          context: context,
          builder: (_) => AlertDialog(
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            title: const Text("Thank You!"),
            content: const Text("Your feedback has been submitted successfully."),
            actions: [
              TextButton(
                onPressed: () => Navigator.of(context).pop(),
                child: const Text("Close"),
              )
            ],
          ),
        );
      });
    }
  }

  InputDecoration _inputDecoration(String label, IconData icon, {int maxLines = 1}) {
    return InputDecoration(
      labelText: label,
      prefixIcon: Icon(icon),
      alignLabelWithHint: maxLines > 1,
      filled: true,
      fillColor: Colors.grey[100],
      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12), borderSide: BorderSide.none),
      contentPadding: const EdgeInsets.symmetric(vertical: 18, horizontal: 16),
    );
  }

  @override
  Widget build(BuildContext context) {
    const primaryColor = Color(0xFF3B38FF);

    return Scaffold(
      appBar: AppBar(
        title: const Text("Feedback"),
        backgroundColor: primaryColor,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(20),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Text(
                "We value your feedback",
                style: TextStyle(fontSize: 22, fontWeight: FontWeight.bold),
              ),
              const SizedBox(height: 10),
              const Text(
                "Please share your thoughts to help us improve.",
                style: TextStyle(fontSize: 14, color: Colors.grey),
              ),
              const SizedBox(height: 30),

              // Name
              TextFormField(
                decoration: _inputDecoration("Name", Icons.person),
                validator: (val) => val == null || val.isEmpty ? "Enter your name" : null,
                onChanged: (val) => name = val,
              ),
              const SizedBox(height: 20),

              // Email
              TextFormField(
                decoration: _inputDecoration("Email", Icons.email),
                keyboardType: TextInputType.emailAddress,
                validator: (val) {
                  if (val == null || val.isEmpty) return "Enter your email";
                  if (!val.contains('@') || !val.contains('.')) return "Enter a valid email";
                  return null;
                },
                onChanged: (val) => email = val,
              ),
              const SizedBox(height: 20),

              // Message
              TextFormField(
                decoration: _inputDecoration("Your Feedback", Icons.feedback_outlined, maxLines: 5),
                maxLines: 5,
                validator: (val) => val == null || val.isEmpty ? "Enter your message" : null,
                onChanged: (val) => message = val,
              ),
              const SizedBox(height: 30),

              // Submit
              SizedBox(
                width: double.infinity,
                child: ElevatedButton(
                  onPressed: isSubmitting ? null : _submitForm,
                  style: ElevatedButton.styleFrom(
                    backgroundColor: primaryColor,
                    padding: const EdgeInsets.symmetric(vertical: 16),
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                  ),
                  child: isSubmitting
                      ? const CircularProgressIndicator(color: Colors.white)
                      : const Text("Submit Feedback", style: TextStyle(fontSize: 16)),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
