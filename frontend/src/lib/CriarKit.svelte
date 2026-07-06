<script>
    import { onMount } from "svelte";
    import { navigate } from "svelte-routing";

    let nome = $state("");
    let localizacao = $state("");

    // Lista de itens que o utilizador pode escolher (apenas os livres)
    let itemsLivres = $state([]);
    // Array que vai acumulando os IDs selecionados automaticamente pelo bind:group
    let itemsDoKitIds = $state([]);

    let erro = $state("");
    let sucesso = $state("");

    onMount(() => {
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
                itemsLivres = data.filter((item) => item.kit_id === null);
            });
    });

    function criarKit(e) {
        e.preventDefault();
        erro = "";
        sucesso = "";

        let localizacaoSubmeter;

        if (localizacao === "") {
            localizacaoSubmeter = null;
        } else {
            localizacaoSubmeter = localizacao;
        }

        fetch("http://127.0.0.1:8000/api/kits", {
            method: "POST",
            mode: "cors",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
            body: JSON.stringify({
                nome: nome,
                localizacao: localizacaoSubmeter,
                items_ids: itemsDoKitIds, // Envia os IDs selecionados
            }),
        })
            .then((res) => {
                if (res.ok) {
                    sucesso = "Kit criado com sucesso, a voltar à lista!";
                    setTimeout(() => navigate("/kits/listar"), 1500);
                } else {
                    erro = "Erro ao criar o kit.";
                }
            })
            .catch(() => {
                erro = "Erro de rede.";
            });
    }
</script>

<nav>
    <button onclick={() => navigate("/menu")}>Voltar ao Menu</button>
</nav>

<hr />

<h3>Novo Kit</h3>

{#if erro}
    <p style="color: red;">{erro}</p>
{/if}
{#if sucesso}
    <p style="color: green;">{sucesso}</p>
{/if}

<form onsubmit={criarKit}>
    <label>
        Nome do Kit: <input type="text" bind:value={nome} required />
    </label>
    <br /><br />
    <label> Localização: <input type="text" bind:value={localizacao} /> </label>

    <h4>Associar Itens Disponíveis</h4>

    <table border="1" align="center">
        <thead>
            <tr>
                <th>Selecionar</th>
                <th>Nome do Item</th>
            </tr>
        </thead>
        <tbody>
            {#each itemsLivres as item}
                <tr>
                    <td>
                        <input
                            type="checkbox"
                            value={item.id}
                            bind:group={itemsDoKitIds}
                        />
                    </td>
                    <td>{item.nome}</td>
                </tr>
            {:else}
                <tr>
                    <td colspan="2" style="text-align: center; color: gray;">
                        Não existem itens disponíveis para associar.
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>

    <br />
    <input type="submit" value="Gravar Kit" />
</form>
