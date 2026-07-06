<script>
    import { onMount } from "svelte";
    import { navigate } from "svelte-routing";

    let { id } = $props();
    const idKitNum = Number(id); // Para poder comparar, o props vem como string

    let nome = $state("");
    let localizacao = $state("");

    let itemsDisponiveis = $state([]);
    let itemsDoKitIds = $state([]);

    let erro = $state("");
    let sucesso = $state("");

    onMount(() => {
        fetch(`http://127.0.0.1:8000/api/kits/${idKitNum}`, {
            method: "GET",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
        })
            .then((res) => res.json())
            .then((data) => {
                nome = data.nome;
                localizacao = data.localizacao ?? "";

                if (data.items) {
                    itemsDoKitIds = data.items.map((item) => item.id);
                }
            });

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
                itemsDisponiveis = data.filter(
                    (item) => item.kit_id === idKitNum || item.kit_id === null,
                );
            });
    });

    function atualizarKit(e) {
        e.preventDefault();
        erro = "";
        sucesso = "";

        let localizacaoSubmeter = localizacao === "" ? null : localizacao;

        fetch(`http://127.0.0.1:8000/api/kits/${idKitNum}`, {
            method: "PUT",
            mode: "cors",
            headers: {
                Accept: "application/json",
                "Content-Type": "application/json",
                Authorization: "Bearer " + localStorage.getItem("token"),
            },
            body: JSON.stringify({
                nome: nome,
                localizacao: localizacaoSubmeter,
                items_ids: itemsDoKitIds,
            }),
        })
            .then((res) => {
                if (res.ok) {
                    sucesso = "Kit atualizado com sucesso, a voltar à lista!";
                    setTimeout(() => navigate("/kits/listar"), 1500);
                } else {
                    erro = "Erro ao gravar as alterações.";
                }
            })
            .catch(() => {
                erro = "Erro de rede.";
            });
    }
</script>

<nav>
    <button onclick={() => navigate("/kits/listar")}>Voltar à listagem</button>
</nav>

<hr />

<h3>Editar Kit</h3>

{#if erro}
    <p style="color: red;">{erro}</p>
{/if}
{#if sucesso}
    <p style="color: green;">{sucesso}</p>
{/if}

<form onsubmit={atualizarKit}>
    <label>
        Nome do Kit: <input type="text" bind:value={nome} required />
    </label>
    <br /><br />
    <label> Localização: <input type="text" bind:value={localizacao} /> </label>

    <h4>Itens no Kit</h4>
    <table border="1" align="center">
        <thead>
            <tr>
                <th>Selecionar</th>
                <th>Nome do Item</th>
            </tr>
        </thead>
        <tbody>
            {#each itemsDisponiveis as item}
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
                    <td colspan="2" style="text-align: center; color: gray; ">
                        Não existem itens disponíveis para este kit.
                    </td>
                </tr>
            {/each}
        </tbody>
    </table>

    <br />
    <input type="submit" value="Gravar Alterações" />
</form>
