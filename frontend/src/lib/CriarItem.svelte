<script>
  import { onMount } from "svelte";
  import { navigate } from "svelte-routing";

  let nome = $state("");
  let validade = $state("");
  let comprado = $state(false);
  let categoriaId = $state("");
  let kitId = $state("");

  let categorias = $state([]);
  let kits = $state([]);
  let erro = $state("");
  let sucesso = $state("");

  onMount(() => {
    fetch("http://127.0.0.1:8000/api/categorias", {
      method: "GET",
      mode: "cors",
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    })
      .then((res) => res.json())
      .then((data) => {
        categorias = data;
      })
      .catch(() => {
        erro = "Erro de rede ao carregar as categorias.";
      });

    fetch("http://127.0.0.1:8000/api/kits", {
      method: "GET",
      mode: "cors",
      headers: {
        Accept: "application/json",
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
    })
      .then((res) => res.json())
      .then((data) => {
        kits = data;
      })
      .catch(() => {
        erro = "Erro de rede ao carregar os kits.";
      });
  });

  function submeterItem(e) {
    e.preventDefault();
    erro = "";
    sucesso = "";

    let validadeSubmeter;
    let categoriaSubmeter;
    let kitSubmeter;

    if (validade === "") {
      validadeSubmeter = null;
    } else {
      validadeSubmeter = validade;
    }

    if (categoriaId === "") {
      categoriaSubmeter = null;
    } else {
      categoriaSubmeter = categoriaId;
    }

    if (kitId === "") {
      kitSubmeter = null;
    } else {
      kitSubmeter = kitId;
    }

    fetch("http://127.0.0.1:8000/api/items", {
      method: "POST",
      mode: "cors",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
        Authorization: "Bearer " + localStorage.getItem("token"),
      },
      body: JSON.stringify({
        nome: nome,
        validade: validadeSubmeter,
        comprado: comprado,
        categoria_id: categoriaSubmeter,
        kit_id: kitSubmeter,
      }),
    })
      .then((res) => {
        if (!res.ok) {
          erro = "O servidor rejeitou a gravação do item.";
          return null;
        }
        return res.json();
      })
      .then((data) => {
        if (data) {
          sucesso = "Item criado com sucesso!";
          // Limpar os campos do formulário para poder inserir mais que um
          nome = "";
          validade = "";
          comprado = false;
          categoriaId = "";
          kitId = "";
        }
      })
      .catch(() => {
        erro = "Erro ao conectar à API ou dados inválidos.";
      });
  }
</script>

<nav>
  <button onclick={() => navigate("/menu")}>Voltar ao Menu</button>
</nav>

<hr />

<h3>Novo Item</h3>

{#if erro}
  <p style="color: red;">{erro}</p>
{/if}

{#if sucesso}
  <p style="color: green;">{sucesso}</p>
{/if}

<form onsubmit={submeterItem}>
  <label>
    Nome do Item:
    <input type="text" bind:value={nome} required />
  </label>
  <br /><br />

  <label>
    Data de Validade:
    <input type="date" bind:value={validade} />
    <small style="display: block; color: gray;"
      >Opcional. Deixe em branco se não tiver validade.</small
    >
  </label>
  <br />

  <label>
    <input type="checkbox" bind:checked={comprado} />
    Marcar como Comprado
  </label>
  <br /><br />

  <label>
    Categoria:
    <select bind:value={categoriaId}>
      <option value="">Sem Categoria</option>
      {#each categorias as categoria}
        <option value={categoria.id}>{categoria.nome}</option>
      {/each}
    </select>
  </label>
  <br /><br />

  <label>
    Kit:
    <select bind:value={kitId}>
      <option value="">Sem Kit</option>
      {#each kits as kit}
        <option value={kit.id}>{kit.nome}</option>
      {/each}
    </select>
  </label>
  <br /><br />

  <input type="submit" value="Gravar Item" />
</form>
