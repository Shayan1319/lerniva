import 'package:flutter/material.dart';
import 'dart:io';
import 'package:image_picker/image_picker.dart';

class ProfilePage extends StatefulWidget {
  final void Function(bool) toggleTheme;
  final ThemeMode themeMode;
  final void Function(bool) toggleNotifications;
  final bool notificationsEnabled;

  const ProfilePage({
    super.key,
    required this.toggleTheme,
    required this.themeMode,
    required this.toggleNotifications,
    required this.notificationsEnabled,
  });

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  final _formKey = GlobalKey<FormState>();
  String firstName = 'Farman';
  String lastName = 'Ullah';
  String email = 'farmanullah@example.com';
  String phone = '+92 300 1234567';
  String bio = 'Software Engineer | Flutter Developer';
  String facebook = '';
  String linkedIn = '';
  String instagram = '';
  File? _image;

  static const Color lurnivaBlue = Color(0xFF3B38FF);

  Future<void> _pickImage() async {
    final picked = await ImagePicker().pickImage(source: ImageSource.gallery);
    if (picked != null) {
      setState(() => _image = File(picked.path));
    }
  }

  InputDecoration _inputDecoration(String label, IconData icon) {
    return InputDecoration(
      labelText: label,
      prefixIcon: Icon(icon, color: lurnivaBlue),
      filled: true,
      fillColor: Colors.grey[100],
      contentPadding: const EdgeInsets.symmetric(vertical: 16, horizontal: 16),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(12),
        borderSide: BorderSide.none,
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[100],
      appBar: AppBar(
        backgroundColor: lurnivaBlue,
        title: const Text(
          "My Profile",
          style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
        ),
        centerTitle: true,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: Form(
        key: _formKey,
        child: ListView(
          children: [
            // 🔹 Header with Avatar
Container(
  height: 160,
  decoration: const BoxDecoration(
    color: Colors.white, // White background instead of blue
    borderRadius: BorderRadius.vertical(bottom: Radius.circular(24)),
  ),
  child: Center(
    child: Stack(
      alignment: Alignment.bottomRight,
      children: [
        CircleAvatar(
          radius: 60,
          backgroundColor: Colors.grey[300], // light grey instead of blue
          backgroundImage: _image != null
              ? FileImage(_image!)
              : const NetworkImage("https://via.placeholder.com/150") as ImageProvider,
          child: _image == null
              ? const Icon(Icons.person, size: 60, color: Colors.white)
              : null,
        ),
        Positioned(
          bottom: 4,
          right: 4,
          child: GestureDetector(
            onTap: _pickImage,
            child: Container(
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: lurnivaBlue,
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.2),
                    blurRadius: 6,
                    offset: const Offset(0, 2),
                  ),
                ],
              ),
              padding: const EdgeInsets.all(8),
              child: const Icon(Icons.edit, size: 18, color: Colors.white),
            ),
          ),
        ),
      ],
    ),
  ),
),


            const SizedBox(height: 20),

            // 🔹 Personal Info
            _buildSectionCard("Personal Info", [
              TextFormField(
                initialValue: firstName,
                decoration: _inputDecoration("First Name", Icons.person_outline),
                onSaved: (val) => firstName = val!,
              ),
              const SizedBox(height: 16),
              TextFormField(
                initialValue: lastName,
                decoration: _inputDecoration("Last Name", Icons.person_outline),
                onSaved: (val) => lastName = val!,
              ),
              const SizedBox(height: 16),
              TextFormField(
                initialValue: bio,
                decoration: _inputDecoration("Biography", Icons.info_outline),
                maxLines: 3,
                onSaved: (val) => bio = val!,
              ),
            ]),

            const SizedBox(height: 20),

            // 🔹 Contact Info
            _buildSectionCard("Contact Info", [
              TextFormField(
                initialValue: email,
                decoration: _inputDecoration("Email", Icons.email),
                onSaved: (val) => email = val!,
              ),
              const SizedBox(height: 16),
              TextFormField(
                initialValue: phone,
                decoration: _inputDecoration("Phone", Icons.phone),
                onSaved: (val) => phone = val!,
              ),
            ]),

            const SizedBox(height: 20),

            // 🔹 Social Links
            _buildSectionCard("Social Links", [
              TextFormField(
                initialValue: facebook,
                decoration: _inputDecoration("Facebook", Icons.facebook),
                onSaved: (val) => facebook = val!,
              ),
              const SizedBox(height: 16),
              TextFormField(
                initialValue: linkedIn,
                decoration: _inputDecoration("LinkedIn", Icons.link),
                onSaved: (val) => linkedIn = val!,
              ),
              const SizedBox(height: 16),
              TextFormField(
                initialValue: instagram,
                decoration: _inputDecoration("Instagram", Icons.camera_alt_outlined),
                onSaved: (val) => instagram = val!,
              ),
            ]),

            const SizedBox(height: 20),

            // 🔹 Preferences
            _buildSectionCard("Preferences", [
              SwitchListTile(
                title: const Text("Dark Mode"),
                value: widget.themeMode == ThemeMode.dark,
                activeColor: lurnivaBlue,
                onChanged: (val) => widget.toggleTheme(val),
              ),
              SwitchListTile(
                title: const Text("Enable Notifications"),
                value: widget.notificationsEnabled,
                activeColor: lurnivaBlue,
                onChanged: (val) => widget.toggleNotifications(val),
              ),
            ]),

            const SizedBox(height: 30),

            // 🔹 Save Button
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: ElevatedButton(
                style: ElevatedButton.styleFrom(
                  backgroundColor: lurnivaBlue,
                  padding: const EdgeInsets.symmetric(vertical: 16),
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                onPressed: () {
                  _formKey.currentState!.save();
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text('Profile updated successfully!')),
                  );
                },
                child: const Text("Save Changes", style: TextStyle(fontSize: 16, color: Colors.white)),
              ),
            ),

            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }

  // 🔹 Helper method for cards
  Widget _buildSectionCard(String title, List<Widget> children) {
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(title,
              style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, color: Colors.black87)),
          const SizedBox(height: 16),
          ...children,
        ],
      ),
    );
  }
}
