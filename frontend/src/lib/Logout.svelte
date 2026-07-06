<script>
    import { navigate } from "svelte-routing";

    function efetuarLogout() {
        const token = localStorage.getItem("token");

        fetch("http://127.0.0.1:8000/api/logout", {
            method: "POST",
            mode: "cors",
            headers: {
                Accept: "application/json",
                Authorization: "Bearer " + token,
            },
        })
            .catch((error) => console.log(error))
            .finally(() => {
                localStorage.removeItem("token");
                navigate("/login", { replace: true });
            });
    }
</script>

<button onclick={efetuarLogout}>Terminar Sessão</button>
