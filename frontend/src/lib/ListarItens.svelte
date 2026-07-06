<script>
    import { onMount } from "svelte";
    import { Link, navigate } from "svelte-routing";

    let items = $state([]);
    let erro = $state("");

    function carregarItens() {
        fetch("http://127.0.0.1:8000/api/items", {
            method: "GET",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => res.json())
            .then((data) => {
                items = data;
            })
            .catch(() => {
                erro = "Erro ao carregar itens!";
            });
    }

    onMount(() => {
        carregarItens();
    });

    function eliminar(id) {
        if (!confirm("Tem a certeza que deseja eliminar este item?")) {
            return;
        }

        fetch("http://127.0.0.1:8000/api/items/" + id, {
            method: "DELETE",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => {
                if (res.ok) {
                    carregarItens();
                } else {
                    erro = "Não foi possível eliminar o item no servidor.";
                }
            })
            .catch(() => {
                erro = "Erro ao tentar conectar à API.";
            });
    }
</script>

<nav>
    <button onclick={() => navigate("/menu")}>Voltar ao Menu</button>
</nav>

<hr />

<h3>Gestão de Itens</h3>

{#if erro}
    <p style="color:red">{erro}</p>
{/if}

<table border="1" align="center">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Kit</th>
            <th>Validade</th>
            <th>Estado</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        {#each items as item}
            <tr>
                <td>{item.nome}</td>
                <td
                    >{item.categoria && item.categoria.nome
                        ? item.categoria.nome
                        : "Sem Categoria"}</td
                >
                <td>{item.kit && item.kit.nome ? item.kit.nome : "Sem Kit"}</td>
                <td>{item.validade ?? "Sem validade"}</td>
                <td>{item.comprado ? "Comprado" : "Pendente"}</td>
                <td>
                    <Link to="/items/editar/{item.id}">Editar</Link>
                    |
                    <button
                        onclick={() => eliminar(item.id)}
                        style="color:darkred;"
                    >
                        Remover
                    </button>
                </td>
            </tr>
        {:else}
            <tr>
                <td colspan="6" style="text-align: center; color: gray;">
                    Não existem itens criados.
                </td>
            </tr>
        {/each}
    </tbody>
</table>
