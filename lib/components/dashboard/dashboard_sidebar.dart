import 'package:flutter/material.dart';

class DashboardSidebar extends StatelessWidget {
  final VoidCallback? onBackPressed;

  const DashboardSidebar({super.key, this.onBackPressed});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        // Header
        Container(
          width: double.infinity,
          padding: const EdgeInsets.symmetric(vertical: 20, horizontal: 16),
          color: Colors.white,
          child: Row(
            children: [
              if (onBackPressed != null)
                IconButton(
                  icon: const Icon(Icons.arrow_back, color: Colors.black),
                  onPressed: onBackPressed,
                ),
              const Icon(Icons.dashboard_customize, size: 32, color: Colors.blue),
              const SizedBox(width: 10),
              Text(
                'Dashboard',
                style: Theme.of(context).textTheme.titleLarge?.copyWith(
                      fontWeight: FontWeight.bold,
                      color: Colors.black87,
                    ),
              ),
            ],
          ),
        ),

        // Gradient Sidebar Items
        Expanded(
          child: Container(
            decoration: const BoxDecoration(
              gradient: LinearGradient(
                colors: [Color(0xFF1DA1F2), Color(0xFF794BC4), Color(0xFF17C3B2)],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: ListView(
              padding: const EdgeInsets.symmetric(vertical: 20),
              children: const [
                SidebarItem(title: 'Dashboard', icon: Icons.dashboard),
                SidebarItem(title: 'Graphs', icon: Icons.bar_chart),
                SidebarItem(title: 'Apps', icon: Icons.apps),
                SidebarItem(title: 'Attendance', icon: Icons.check_circle),
                SidebarItem(title: 'Time Table', icon: Icons.schedule),
                SidebarItem(title: 'Fee', icon: Icons.payment),
                SidebarItem(title: 'Email', icon: Icons.email),
                SidebarItem(title: 'Forms', icon: Icons.article),
                SidebarItem(title: 'Managements', icon: Icons.manage_accounts),
              ],
            ),
          ),
        ),
      ],
    );
  }
}

class SidebarItem extends StatelessWidget {
  final String title;
  final IconData icon;

  const SidebarItem({super.key, required this.title, required this.icon});

  @override
  Widget build(BuildContext context) {
    return ListTile(
      leading: Icon(icon, color: Colors.white),
      title: Text(
        title,
        style: const TextStyle(color: Colors.white),
      ),
      onTap: () {},
    );
  }
}
