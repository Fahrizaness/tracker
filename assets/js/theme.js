document.addEventListener('DOMContentLoaded', function() {
  const themeToggle = document.getElementById('themeToggle');
  const html = document.documentElement;

  // Inisialisasi tema - cek localStorage dulu, lalu preferensi sistem
  const savedTheme = localStorage.getItem('theme') || 
                    (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  
  html.setAttribute('data-bs-theme', savedTheme);
  updateThemeIcon(savedTheme);

  // Toggle handler
  themeToggle?.addEventListener('click', () => {
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme); // Simpan ke localStorage
    updateThemeIcon(newTheme);
  });

  function updateThemeIcon(theme) {
    if (!themeToggle) return;
    themeToggle.innerHTML = theme === 'dark' 
      ? '<i class="bi bi-moon"></i>' 
      : '<i class="bi bi-sun"></i>';
  }

  console.log('Theme initialized:', savedTheme); // Debug log
});