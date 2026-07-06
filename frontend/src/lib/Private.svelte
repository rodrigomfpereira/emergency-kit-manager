<script>
  import { navigate } from "svelte-routing";
  import { onMount, tick } from "svelte";
  import { isAuth } from "./auth.js";

  let { component: Component, componentProps = {} } = $props();

  onMount(() => {
    if (!isAuth()) {
      tick().then(() => navigate("/login", { replace: true }));
    }
  });
</script>

<Component {...componentProps} />
