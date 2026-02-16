{{-- Include in head to set theme from localStorage and define toggleTheme() --}}
<script>
(function(){
var k='lumina-theme',s=localStorage.getItem(k),d=window.matchMedia&&window.matchMedia('(prefers-color-scheme: dark)').matches;
var t=s==='dark'||s==='light'?s:(d?'dark':'light');
if(t==='dark')document.documentElement.classList.add('dark');else document.documentElement.classList.remove('dark');
if(!s)localStorage.setItem(k,t);
window.toggleTheme=function(){var isDark=document.documentElement.classList.toggle('dark');localStorage.setItem(k,isDark?'dark':'light');};
})();
</script>
