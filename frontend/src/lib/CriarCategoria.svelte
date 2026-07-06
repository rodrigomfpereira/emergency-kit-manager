<script>
  import { navigate } from "svelte-routing";

  let nomeCategoria = $state("");
  let erro = $state("");
  let sucesso = $state("");

  function submeterCategoria(e) {
    e.preventDefault();
    erro = "";
    sucesso = "";

    const token = localStorage.getItem("token");

    fetch("http://127.0.0.1:8000/api/categorias", {
      method: "POST",
      mode: "cors",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + token,
      },
      body: JSON.stringify({ nome: nomeCategoria }),
    })
      .then((res) => {
        if (!res.ok) {
          erro = "Não foi possível gravar a categoria.";
          return null;
        }
        return res.json();
      })
      .then((data) => {
        if (data) {
          sucesso = "Categoria criada com sucesso!";
          nomeCategoria = ""; // Limpar o campo para uma nova inserção
        }
      })
      .catch((error) => {
        console.log(error);
        erro = "Erro ao conectar à API ou dados inválidos.";
      });
  }
</script>

<nav>
  <button onclick={() => navigate("/menu")}>Voltar ao Menu</button>
</nav>

<hr />

<h3>Nova Categoria</h3>

{#if erro}
  <p style="color: red;">{erro}</p>
{/if}

{#if sucesso}
  <p style="color: green;">{sucesso}</p>
{/if}

<form
  onsubmit={(e) => {
    submeterCategoria(e);
  }}
>
  <label
    >Nome da Categoria: <input
      type="text"
      bind:value={nomeCategoria}
      required
    /></label
  ><br /><br />
  <input type="submit" value="Gravar Categoria" />
</form>
