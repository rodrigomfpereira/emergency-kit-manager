<script>
    import { onMount } from "svelte";
    import { navigate } from "svelte-routing";

    let { id } = $props();

    let nomeCategoria = $state("");
    let erro = $state("");
    let sucesso = $state("");

    onMount(() => {
        fetch(`http://127.0.0.1:8000/api/categorias/${id}`, {
            method: "GET",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => {
                if (!res.ok) {
                    erro = "Categoria não encontrada no servidor.";
                    return null;
                }
                return res.json();
            })
            .then((data) => {
                if (data && data.nome) {
                    nomeCategoria = data.nome;
                }
            })
            .catch(() => {
                erro = "Erro de rede ao carregar os dados.";
            });
    });

    function atualizarCategoria(e) {
        e.preventDefault();
        erro = "";
        sucesso = "";

        fetch(`http://127.0.0.1:8000/api/categorias/${id}`, {
            method: "PUT",
            mode: "cors",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
            body: JSON.stringify({ nome: nomeCategoria }),
        })
            .then((res) => {
                if (res.ok) {
                    sucesso =
                        "Categoria atualizada com sucesso, a voltar à lista!";

                    // para o utilizador conseguir ler a mensagem
                    setTimeout(() => {
                        navigate("/categorias/listar");
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
    <button onclick={() => navigate("/categorias/listar")}
        >Voltar à Listagem</button
    >
</nav>

<hr />

<h3>Editar Categoria</h3>

{#if erro}
    <p style="color: red;">{erro}</p>
{/if}

{#if sucesso}
    <p style="color: green;">{sucesso}</p>
{/if}

<form onsubmit={atualizarCategoria}>
    <label>
        Nome da Categoria:
        <input type="text" bind:value={nomeCategoria} required />
    </label>
    <br /><br />
    <input type="submit" value="Gravar Alterações" />
</form>
