<script>
    import { onMount } from "svelte";
    import { Link, navigate } from "svelte-routing";

    let categorias = $state([]);
    let erro = $state("");

    function carregarCategorias() {
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
                erro = "Erro ao carregar as categorias!";
            });
    }

    onMount(() => {
        carregarCategorias();
    });

    function temProdutos(aCategoria) {
        if (aCategoria.items && aCategoria.items.length > 0) {
            return true;
        }
        return false;
    }

    function eliminar(id) {
        if (!confirm("Tem a certeza que deseja eliminar esta categoria?")) {
            return;
        }

        fetch("http://127.0.0.1:8000/api/categorias/" + id, {
            method: "DELETE",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => {
                if (res.ok) {
                    carregarCategorias();
                } else {
                    erro = "Não foi possível eliminar a categoria no servidor.";
                }
            })
            .catch(() => {
                erro = "Erro ao tentar conectar à API.";
            });
    }

    function textoDoBotaoEliminar(aCategoria) {
        if (temProdutos(aCategoria)) {
            return "Não pode eliminar, categoria contém itens.";
        }
        return "Eliminar esta categoria";
    }
</script>

<nav>
  <button onclick={() => navigate("/menu")}>Voltar ao Menu</button>
</nav>

<hr />

<h3>Gestão de Categorias</h3>

{#if erro}
    <p style="color:red">{erro}</p>
{/if}

<table border="1" align="center">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Quantidade de itens</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        {#each categorias as categoria}
            <tr>
                <td>{categoria.nome}</td>
                <td>{categoria.items.length ?? 0}</td>
                <td>
                    <Link to="/categorias/editar/{categoria.id}">Editar</Link>
                    |
                    <button
                        disabled={temProdutos(categoria)}
                        onclick={() => eliminar(categoria.id)}
                        title={textoDoBotaoEliminar(categoria)}
                        style="color:darkred;"
                        >Remover
                    </button>
                </td>
            </tr>
        {:else}
            <tr>
                <td colspan="3" style="text-align: center; color: gray;">
                    Não existem categorias criadas.
                </td>
            </tr>
        {/each}
    </tbody>
</table>