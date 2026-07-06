<script>
  import { Router, Route } from "svelte-routing";
  import { isAuth } from "./lib/auth.js";

  import Login from "./lib/Login.svelte";
  import Registo from "./lib/Registo.svelte";
  import Menu from "./lib/Menu.svelte";
  import Private from "./lib/Private.svelte";
  import CriarCategoria from "./lib/CriarCategoria.svelte";
  import ListarCategorias from "./lib/ListarCategorias.svelte";
  import EditarCategoria from "./lib/EditarCategoria.svelte";
  import ListarItens from "./lib/ListarItens.svelte";
  import EditarItem from "./lib/EditarItem.svelte";
  import CriarItem from "./lib/CriarItem.svelte";
  import ListarKits from "./lib/ListarKits.svelte";
  import EditarKit from "./lib/EditarKit.svelte";
  import CriarKit from "./lib/CriarKit.svelte";

  export let url = "";
</script>

<Router {url}>
  <div id="conteudo">
    <Route path="/">
      {#if isAuth()}
        <Private component={Menu} />
      {:else}
        <Login />
      {/if}
    </Route>

    <Route path="/login"><Login /></Route>
    <Route path="/registo"><Registo /></Route>

    <Route path="/menu">
      <Private component={Menu} />
    </Route>

    <Route path="/categorias/criar">
      <Private component={CriarCategoria} />
    </Route>
    <Route path="/categorias/listar">
      <Private component={ListarCategorias} />
    </Route>
    <Route path="/categorias/editar/:id" let:params>
      <Private component={EditarCategoria} componentProps={{ id: params.id }} />
    </Route>

    <Route path="/items/listar">
      <Private component={ListarItens} />
    </Route>
    <Route path="/items/editar/:id" let:params>
      <Private component={EditarItem} componentProps={{ id: params.id }} />
    </Route>
    <Route path="/items/criar">
      <Private component={CriarItem} />
    </Route>

    <Route path="/kits/listar">
      <Private component={ListarKits} />
    </Route>
    <Route path="/kits/editar/:id" let:params>
      <Private component={EditarKit} componentProps={{ id: params.id }} />
    </Route>
    <Route path="/kits/criar">
      <Private component={CriarKit} />
    </Route>
  </div>
</Router>
