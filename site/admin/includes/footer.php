    </main>
</div>
<button class="menu-toggle" onclick="document.querySelector('.sidebar').classList.toggle('active')">☰</button>
<script>
document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.menu-toggle');
    if (window.innerWidth <= 768 && sidebar && toggle && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
        sidebar.classList.remove('active');
    }
});
</script>
</body>
</html>
