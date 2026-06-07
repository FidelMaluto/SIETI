const menuToggle = document.getElementById('menuToggle');
const sidebar = document.querySelector('.sidebar');

menuToggle.addEventListener('click', () => {
  sidebar.classList.toggle('active');
});

// Filter de históricos
const search = document.getElementById('searchInput');
const tabela = document.getElementById('filtrando');
const linhas = tabela.getElementsByTagName('tr');

search.addEventListener('keyup', () => {
  const filtro = search.value.toLowerCase();

  for (let i = 0; i < linhas.length; i++) {
    const coluna = linhas[i].getElementsByTagName('td')[1];
    if (coluna) {
      const texto = coluna.textContent.toLowerCase();

      linhas[i].style.display = texto.includes(filtro) ? "" : "none";
    }
  }
});
