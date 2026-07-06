<script>
    import { onMount } from "svelte";
    import { Link, navigate } from "svelte-routing";

    let kits = $state([]);
    let erro = $state("");

    function carregarKits() {
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
                erro = "Erro ao carregar os kits!";
            });
    }

    onMount(() => {
        carregarKits();
    });

    function temItens(aKit) {
        if (aKit.items && aKit.items.length > 0) {
            return true;
        }
        return false;
    }

    function eliminarKit(id) {
        if (!confirm("Tem a certeza que deseja eliminar este kit?")) {
            return;
        }

        fetch("http://127.0.0.1:8000/api/kits/" + id, {
            method: "DELETE",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => {
                if (res.ok) {
                    carregarKits();
                } else {
                    erro = "Não foi possível eliminar o kit no servidor.";
                }
            })
            .catch(() => {
                erro = "Erro ao tentar conectar à API.";
            });
    }

    function textoDoBotaoEliminar(aKit) {
        if (temItens(aKit)) {
            return "Não pode eliminar, o kit contém itens associados.";
        }
        return "Eliminar este kit";
    }

    function eliminarItem(id) {
        if (
            !confirm(
                "Tem a certeza que deseja eliminar este item definitivamente?",
            )
        ) {
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
                    carregarKits(); // Recarrega a página para atualizar as tabelas
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
<br />
<h3>Gestão de Kits</h3>
<br />
<hr />

{#if erro}
    <p style="color:red">{erro}</p>
{/if}

{#each kits as kit}
    <h4>{kit.nome}</h4>
    <p><strong>Localização:</strong> {kit.localizacao ?? "Sem localização"}</p>
    <br />

    <table border="1" align="center">
        <thead>
            <tr>
                <th>Nome do Item</th>

                <th>Validade</th>
                <th>Estado</th>
                <th>Ações do Item</th>
            </tr>
        </thead>
        <tbody>
            {#if kit.items && kit.items.length > 0}
                {#each kit.items as item}
                    <tr>
                        <td>{item.nome}</td>

                        <td>{item.validade ?? "Sem validade"}</td>
                        <td>{item.comprado ? "Comprado" : "Pendente"}</td>
                        <td>
                            <Link to="/items/editar/{item.id}">Editar</Link>
                            |
                            <button
                                onclick={() => eliminarItem(item.id)}
                                style="color: darkred;"
                            >
                                Remover
                            </button>
                        </td>
                    </tr>
                {/each}
            {:else}
                <tr>
                    <td colspan="4" style="color: gray; text-align: center;">
                        Este kit ainda não tem itens associados.
                    </td>
                </tr>
            {/if}
        </tbody>
    </table>

    <br />
    <button onclick={() => navigate(`/kits/editar/${kit.id}`)}
        >Editar Kit</button
    >
    <button
        disabled={temItens(kit)}
        onclick={() => eliminarKit(kit.id)}
        title={textoDoBotaoEliminar(kit)}
        style="color:darkred;"
    >
        Apagar Kit
    </button>

    <br /><br />
    <hr />
{:else}
    <p>Não existem kits criados.</p>
{/each}
