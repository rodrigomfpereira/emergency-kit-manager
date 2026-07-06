<script>
    import { onMount } from "svelte";
    import { navigate } from "svelte-routing";

    let { id } = $props();

    // Estados do formulário
    let nome = $state("");
    let validade = $state("");
    let comprado = $state(false);
    let categoriaId = $state("");
    let kitId = $state(null);
    let categorias = $state([]);
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

        fetch(`http://127.0.0.1:8000/api/items/${id}`, {
            method: "GET",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => {
                if (!res.ok) {
                    erro = "Item não encontrado no servidor.";
                    return null;
                }
                return res.json();
            })
            .then((data) => {
                if (data) {
                    nome = data.nome;
                    validade = data.validade ?? "";
                    comprado = Boolean(data.comprado);
                    categoriaId = data.categoria_id ?? "";
                    kitId = data.kit_id ?? null;
                }
            })
            .catch(() => {
                erro = "Erro de rede ao carregar os dados do item.";
            });
    });

    function atualizarItem(e) {
        e.preventDefault();
        erro = "";
        sucesso = "";

        let validadeSubmeter;
        let categoriaSubmeter;

        if (validade === "") {
            validadeSubmeter = null; // limpa na bd
        } else {
            validadeSubmeter = validade; // altera a data
        }

        if (categoriaId === "") {
            categoriaSubmeter = null; // limpa na bd
        } else {
            categoriaSubmeter = categoriaId; // altera o id
        }
        fetch(`http://127.0.0.1:8000/api/items/${id}`, {
            method: "PUT",
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
                kit_id: kitId,
            }),
        })
            .then((res) => {
                if (res.ok) {
                    sucesso = "Item atualizado com sucesso, a voltar à lista!";
                    setTimeout(() => {
                        history.back();
                    }, 1500);
                } else {
                    erro = "O servidor rejeitou a alteração (dados inválidos).";
                }
            })
            .catch(() => {
                erro = "Erro de rede ao tentar atualizar.";
            });
    }
</script>

<nav>
    <button onclick={() => history.back()}>Voltar</button>
</nav>

<hr />

<h3>Editar Item</h3>

{#if erro}
    <p style="color: red;">{erro}</p>
{/if}

{#if sucesso}
    <p style="color: green;">{sucesso}</p>
{/if}

<form onsubmit={atualizarItem}>
    <label>
        Nome do Item:
        <input type="text" bind:value={nome} required />
    </label>
    <br /><br />

    <label>
        Data de Validade:
        <input type="date" bind:value={validade} />
        <small style="display: block; color: gray;"
            >Deixe em branco se pretender remover/anular a validade.</small
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

    <input type="submit" value="Gravar Alterações" />
</form>
