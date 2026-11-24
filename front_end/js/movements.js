document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll('[data-go="products"]').forEach(button =>
    button.addEventListener("click", () => location.href = "produtos.php")
  );
  document.querySelectorAll('[data-go="movements"]').forEach(button =>
    button.addEventListener("click", () => location.href = "movimentacoes.php")
  );

  const modal = document.getElementById("movementModal");
  if (!modal) return;

  const openModal = () => {
    modal.classList.add("open");
    modal.setAttribute("aria-hidden", "false");
    const firstField = modal.querySelector("input, select");
    if (firstField) firstField.focus();
  };
  const closeModal = () => {
    modal.classList.remove("open");
    modal.setAttribute("aria-hidden", "true");
  };

  document.getElementById("newMovementBtn")?.addEventListener("click", openModal);
  modal.querySelectorAll("[data-close-modal]").forEach(button =>
    button.addEventListener("click", closeModal)
  );
  modal.addEventListener("click", event => {
    if (event.target === modal) closeModal();
  });
  document.addEventListener("keydown", event => {
    if (event.key === "Escape" && modal.classList.contains("open")) closeModal();
  });

  // A tela ainda não está conectada ao PHP/MySQL; evita recarregar ao enviar.
  modal.querySelector("form")?.addEventListener("submit", event => event.preventDefault());
});
