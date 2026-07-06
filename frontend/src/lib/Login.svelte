<script>
  import { onMount } from "svelte";
  import { Link, navigate } from "svelte-routing";
  import { isAuth } from "./auth.js"; 

  let emailForm = $state("");
  let passwordForm = $state("");
  let erro = $state("");

  onMount(() => {
    if (isAuth()) {
      navigate("/menu", { replace: true });
    }
  });

  function autentica(e) {
    e.preventDefault();
    erro = "";

    fetch("http://127.0.0.1:8000/api/login", {
      method: "POST",
      mode: "cors",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email: emailForm, password: passwordForm }),
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.token) {
          localStorage.setItem("token", data.token);
          navigate("/menu", { replace: true });
        } else {
          erro = "Credenciais inválidas!";
        }
      })
      .catch((error) => console.log(error));
  }
</script>

<nav>
  <Link to="/registo">Ir para Registo</Link>
</nav>

<hr />

<h3>Iniciar Sessão</h3>

{#if erro}
  <p style="color:red">{erro}</p>
{/if}

<form
  onsubmit={(e) => {
    autentica(e);
  }}
>
  <label>E-mail: <input type="email" bind:value={emailForm} required /></label><br />
  <label
    >Password: <input
      type="password"
      bind:value={passwordForm}
      required
    /></label
  ><br /><br />
  <input type="submit" value="Autenticar" />
</form>