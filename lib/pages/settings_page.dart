import 'package:flutter/material.dart';

class SettingsPage extends StatefulWidget {
  final void Function(bool) toggleTheme;
  final ThemeMode themeMode;
  final void Function(bool) toggleNotifications;
  final bool notificationsEnabled;

  const SettingsPage({
    super.key,
    required this.toggleTheme,
    required this.themeMode,
    required this.toggleNotifications,
    required this.notificationsEnabled,
  });

  @override
  State<SettingsPage> createState() => _SettingsPageState();
}

class _SettingsPageState extends State<SettingsPage> {
  String _selectedLanguage = 'English';

  void _confirmLogout(BuildContext context) {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Logout"),
        content: const Text("Are you sure you want to logout?"),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text("Cancel")),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              Navigator.pushReplacementNamed(context, '/login');
            },
            child: const Text("Logout"),
          ),
        ],
      ),
    );
  }

  void _confirmDeleteAccount() {
    showDialog(
      context: context,
      builder: (_) => AlertDialog(
        title: const Text("Delete Account"),
        content: const Text("This will permanently delete your account. Proceed?"),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text("Cancel")),
          ElevatedButton(
            onPressed: () {
              Navigator.pop(context);
              ScaffoldMessenger.of(context).showSnackBar(
                const SnackBar(content: Text("Account deletion feature coming soon!")),
              );
            },
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: const Text("Delete"),
          ),
        ],
      ),
    );
  }

  static const Color lurnivaBlue = Color(0xFF3B38FF);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Settings"),
        backgroundColor: lurnivaBlue,
        centerTitle: true,
      ),
      body: ListView(
        padding: const EdgeInsets.all(16),
        children: [
          _sectionTitle("Account"),
          _card([
            ListTile(
              leading: const Icon(Icons.person, color: lurnivaBlue),
              title: const Text("View/Edit Profile"),
              onTap: () => Navigator.pushNamed(context, '/profile'),
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.lock, color: lurnivaBlue),
              title: const Text("Change Password"),
              onTap: () {
                // Navigate to change password page
              },
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.school, color: lurnivaBlue),
              title: const Text("Switch School"),
              onTap: () {
                // Placeholder
              },
            ),
          ]),

          _sectionTitle("App Preferences"),
          _card([
            SwitchListTile(
              title: const Text("Dark Mode"),
              secondary: const Icon(Icons.dark_mode, color: lurnivaBlue),
              value: widget.themeMode == ThemeMode.dark,
              onChanged: widget.toggleTheme,
            ),
            _divider(),
            SwitchListTile(
              title: const Text("Notifications"),
              secondary: const Icon(Icons.notifications, color: lurnivaBlue),
              value: widget.notificationsEnabled,
              onChanged: widget.toggleNotifications,
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.language, color: lurnivaBlue),
              title: const Text("Language"),
              trailing: DropdownButton<String>(
                value: _selectedLanguage,
                underline: const SizedBox(),
                onChanged: (val) => setState(() => _selectedLanguage = val!),
                items: const [
                  DropdownMenuItem(value: 'English', child: Text('English')),
                  DropdownMenuItem(value: 'Urdu', child: Text('Urdu')),
                ],
              ),
            ),
          ]),

          _sectionTitle("System & Support"),
          _card([
            ListTile(
              leading: const Icon(Icons.help, color: lurnivaBlue),
              title: const Text("Help & Support"),
              onTap: () => Navigator.pushNamed(context, '/help'),
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.privacy_tip, color: lurnivaBlue),
              title: const Text("Privacy Policy"),
              onTap: () {
                // Show privacy policy
              },
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.info_outline, color: lurnivaBlue),
              title: const Text("App Version"),
              subtitle: const Text("v1.0.0"),
              onTap: () {},
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.update, color: lurnivaBlue),
              title: const Text("Check for Updates"),
              onTap: () {
                ScaffoldMessenger.of(context).showSnackBar(
                  const SnackBar(content: Text("You’re on the latest version")),
                );
              },
            ),
          ]),

          _sectionTitle("Danger Zone"),
          _card([
            ListTile(
              leading: const Icon(Icons.delete_forever, color: Colors.red),
              title: const Text("Delete Account"),
              onTap: _confirmDeleteAccount,
            ),
            _divider(),
            ListTile(
              leading: const Icon(Icons.logout, color: Colors.red),
              title: const Text("Logout"),
              onTap: () => _confirmLogout(context),
            ),
          ]),
        ],
      ),
    );
  }

  Widget _sectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.only(top: 24, bottom: 8),
      child: Text(title, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
    );
  }

  Widget _divider() => const Divider(height: 1);

  Widget _card(List<Widget> children) {
    return Card(
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Column(children: children),
    );
  }
}
