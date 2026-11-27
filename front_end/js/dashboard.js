document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('[data-go="products"]').forEach(button =>
    button.addEventListener("click", () => location.href = "produtos.php")
  );
  const modal = document.getElementById("productModal");
  const openModal = () => {
    modal?.classList.add("open");
    modal?.setAttribute("aria-hidden", "false");
    modal?.querySelector("input, select")?.focus();
  };
  const closeModal = () => {
    modal?.classList.remove("open");
    modal?.setAttribute("aria-hidden", "true");
  };

  document.getElementById("quickAdd")?.addEventListener("click", openModal);
  modal?.querySelectorAll("[data-close-modal]").forEach(button =>
    button.addEventListener("click", closeModal)
  );
  modal?.addEventListener("click", event => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener("keydown", event => {
    if (event.key === "Escape" && modal?.classList.contains("open")) closeModal();
  });
  modal?.querySelector("form")?.addEventListener("submit", event => event.preventDefault());
});
