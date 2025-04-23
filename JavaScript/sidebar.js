const toggleBtn = document.getElementById("toggleSidebar");
  const sidebar = document.getElementById("sidebar");

  toggleBtn.addEventListener("click", () => {
    // Abrindo
    if (sidebar.classList.contains("collapsed")) {
      sidebar.classList.remove("collapsed");
      sidebar.classList.remove("hide-text");
    } else {
      // Fechando: primeiro colapsa, depois esconde o texto
      sidebar.classList.add("collapsed");
      setTimeout(() => {
        sidebar.classList.add("hide-text");
      }, 300); // mesmo tempo da transição de width
    }
  });