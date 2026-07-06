<script>
  import { Link } from "svelte-routing";
  import Logout from "./Logout.svelte";
</script>

<nav>
  <span>Menu do Inventário</span> | <Logout />
</nav>

<hr />
<br />

<Link to="/categorias/criar">Criar Categoria</Link><br /><br />
<Link to="/categorias/listar">Listar Categorias</Link><br /><br /><br /><br />
<Link to="/items/criar">Criar Item</Link><br /><br />
<Link to="/items/listar">Listar Itens</Link><br /><br /><br /><br />
<Link to="/kits/criar">Criar Kit</Link><br /><br />
<Link to="/kits/listar">Listar Kits</Link>
