import re
import os

filepath = r'c:\xampp\htdocs\flux\index.php'
with open(filepath, 'r', encoding='utf-8') as f:
    html = f.read()

# Ensure we have darkMode: 'class' in tailwind.config
if "darkMode: 'class'" not in html:
    html = html.replace('tailwind.config = {', "tailwind.config = {\n            darkMode: 'class',")

# Class mapping rules
class_mapping = {
    'bg-white': 'dark:bg-slate-900',
    'bg-slate-50': 'dark:bg-slate-950',
    'bg-white/85': 'dark:bg-slate-900/85',
    'text-slate-900': 'dark:text-white',
    'text-slate-800': 'dark:text-slate-200',
    'text-slate-700': 'dark:text-slate-300',
    'text-slate-600': 'dark:text-slate-400',
    'text-slate-500': 'dark:text-slate-400',
    'text-slate-400': 'dark:text-slate-500',
    'border-slate-100': 'dark:border-slate-800',
    'border-slate-200': 'dark:border-slate-700',
    'hover:bg-slate-50': 'dark:hover:bg-slate-800',
    'hover:bg-slate-100': 'dark:hover:bg-slate-800',
    'hover:text-slate-900': 'dark:hover:text-white',
    'bg-slate-900': 'dark:bg-white',
    'text-white': 'dark:text-slate-900',
    'shadow-slate-300/25': 'dark:shadow-slate-900/50',
    'bg-slate-800/50': 'dark:bg-slate-800/80',
    'border-slate-700/50': 'dark:border-slate-700',
}

# Exemptions
exemptions = {
    'bg-slate-900': lambda c: 'text-white' in c, # maybe button
}

def apply_dark_classes(match):
    cls_str = match.group(1)
    classes = cls_str.split()
    new_classes = list(classes)
    
    # Check if we should ignore (e.g., if it's already got dark: classes or if it's the FAQ/footer area that is already dark)
    
    for c in classes:
        if c in class_mapping:
            dark_c = class_mapping[c]
            # don't add if a dark version of this prop already exists
            prefix = dark_c.split('-')[0] + '-' # dark:bg- or dark:text-
            if not any(dc.startswith(prefix) for dc in classes):
                # special cases
                if c == 'bg-slate-900' and 'bg-slate-800/50' not in classes:
                    # In footer, it's bg-slate-950, which is fine
                    new_classes.append('dark:bg-slate-800')
                    # wait, button has bg-slate-900 text-white
                    if 'text-white' in classes:
                        new_classes.append('dark:text-white') # wait, text-white should remain white for button? No, let's keep button text white and bg brand.
                        # Actually let's manually patch some sections
                else:
                    if dark_c not in new_classes:
                        new_classes.append(dark_c)
                        
    return 'class="' + ' '.join(new_classes) + '"'

html = re.sub(r'class="([^"]+)"', apply_dark_classes, html)

# Let's fix specific conflicting classes or add the theme toggle switch.

# Adding the theme toggle button next to 'Get Started'
toggle_btn = """
                <button id="themeToggleBtn" class="p-2 rounded-full text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors ml-4" aria-label="Toggle dark mode">
                    <svg id="themeIconSun" class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg id="themeIconMoon" class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>
"""
if 'id="themeToggleBtn"' not in html:
    html = html.replace('<button id="menuBtn"', toggle_btn + '\n                <button id="menuBtn"')

theme_script = """
            // ── Theme toggle ───────────────────────────────────────────
            const themeBtn = document.getElementById('themeToggleBtn');
            const htmlEl = document.documentElement;
            
            // Check local storage or system preference
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                htmlEl.classList.add('dark');
            } else {
                htmlEl.classList.remove('dark');
            }

            themeBtn.addEventListener('click', () => {
                htmlEl.classList.toggle('dark');
                if (htmlEl.classList.contains('dark')) {
                    localStorage.theme = 'dark';
                } else {
                    localStorage.theme = 'light';
                }
            });
"""
if 'Theme toggle' not in html:
    html = html.replace('// ── Mobile menu toggle', theme_script + '\n            // ── Mobile menu toggle')

# specific fixes: the footer is already dark, we should not add dark:bg-white to it.
html = html.replace('class="bg-slate-950 dark:bg-slate-800', 'class="bg-slate-950')
# The testimonials bg is bg-slate-900, we don't want it turning white
html = html.replace('class="py-24 bg-slate-900 dark:bg-slate-800 text-white dark:text-slate-900', 'class="py-24 bg-slate-900 text-white')

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(html)

print("Done applying dark mode!")
