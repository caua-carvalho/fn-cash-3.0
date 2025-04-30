const toggleButton = document.getElementById("toggle-btn");
const sidebar = document.getElementById("sidebar");

function toggleSidebar() {
  sidebar.classList.toggle("close");
  toggleButton.classList.toggle("rotate");
  closeAllSubMenus();
  saveSidebarState();
}

function toggleSubMenu(button) {
  if (!button.nextElementSibling.classList.contains("show")) {
    closeAllSubMenus();
  }
  button.nextElementSibling.classList.toggle("show");
  button.classList.toggle("rotate");
  if (sidebar.classList.contains("close")) {
    sidebar.classList.toggle("close");
    toggleButton.classList.toggle("rotate");
  }
  saveSidebarState();
}

function closeAllSubMenus() {
  Array.from(sidebar.getElementsByClassName("show")).forEach((ul) => {
    ul.classList.remove("show");
    ul.previousElementSibling.classList.remove("rotate");
  });
}

function saveSidebarState() {
  const isSidebarClosed = sidebar.classList.contains("close");
  const subMenuStates = {};

  Array.from(sidebar.querySelectorAll(".submenu")).forEach((submenu, index) => {
    subMenuStates[index] = submenu.classList.contains("show");
  });

  const state = {
    sidebar: isSidebarClosed,
    subMenus: subMenuStates,
  };

  // Armazenando o estado no localStorage
  localStorage.setItem("sidebarState", JSON.stringify(state));
}

function loadSidebarState() {
  const savedState = localStorage.getItem("sidebarState");
  if (savedState) {
    const { sidebar: isSidebarClosed, subMenus } = JSON.parse(savedState);

    if (isSidebarClosed) {
      sidebar.classList.add("close");
      toggleButton.classList.add("rotate");
    }

    Object.keys(subMenus).forEach((index) => {
      const submenu = sidebar.querySelectorAll(".submenu")[index];
      if (submenu) {
        if (subMenus[index]) {
          submenu.classList.add("show");
          submenu.previousElementSibling.classList.add("rotate");
        } else {
          submenu.classList.remove("show");
          submenu.previousElementSibling.classList.remove("rotate");
        }
      }
    });
  }
}

// Carregar o estado da sidebar ao carregar a página
loadSidebarState();
