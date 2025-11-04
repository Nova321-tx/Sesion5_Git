<%@ Page Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="Principal.aspx.cs" Inherits="AppInteractive.Principal" %>
<asp:Content ID="Content1" ContentPlaceHolderID="MainContent" runat="server">
    <div class="evento">
        <asp:ImageButton ID="btnGuia" runat="server" ImageUrl="Img/GuiaMicrosoft.png" Onclick="btnGuia_click" CssClass="evento-image" OnClientClick="return checkConnection();"/>
        <asp:ImageButton ID="btnRest" runat="server" ImageUrl="Img/Restablecer.png" OnClick="btnRest_click" CssClass="evento-image" OnClientClick="return checkConnection();"/>
        <asp:ImageButton ID="btnAcceso" runat="server" ImageUrl="Img/AccesoWiFi.png" OnClick="btnAcceso_click" CssClass="evento-image" OnClientClick="return checkConnection();"/>    
        <asp:ImageButton ID="btnPago" runat="server" ImageUrl="Img/Pagos.png" OnClick="btnPago_click" CssClass="evento-image" OnClientClick="return checkPayment();"/>
    </div>
    <div class="iframe-visible" style="display:none;">
        <iframe id="iframeContenido" src="" frameborder="0"></iframe>
    </div>
    <div id="modalOffline" class="modal-offline" style="display: none;">
        <div class="modal-content">
            <p>⚠️ Servicio no disponible temporalmente</p>
        </div>
    </div>
<style>

body {
    font-family: Arial, sans-serif;
}
 
.evento {
    display: flex;
    justify-content: center;
    gap: 50px;
}

.evento-image {
    width: 250px;
    height: auto;
    transition: transform 0.3s;
}

.evento-image:hover {
    transform: scale(1.2);
}

.advertisement {
    display: none; /* Ocultar por defecto */
    background-color: #f0f0f0;
    padding: 20px;
    text-align: center;
    margin-top: 20px;
}
.inicio{
    position: fixed;
    bottom: 10px;
    right: 10px;
    width: auto;
}
.evento-inici{
    right: 10px;
    left: 10px;
}
/* Estilos para la alerta de pagos */
/* Modal de Servicio No Disponible */
.modal-offline {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0,0,0,0.7);
    justify-content: center;
    align-items: center;
    z-index: 9999;
}
.modal-offline .modal-content {
    background: #051636;
    padding: 20px;
    border-radius: 8px;
    text-align: center;
    font-size: 18px;
    font-weight: bold;
    color: white;
}


</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Función para verificar la conexión y decidir qué hacer
        window.checkPayment = function () {
            if (!navigator.onLine) {
                mostrarVentanaOffline();
                return false; // Cancela el postback
            } else {
                // Si hay conexión, se permite que se llame al btnPago_click en el servidor,
                // pero en este caso el script del servidor mostrará la alerta.
                return true;
            }
        };

        // Función para mostrar modal "Servicio no disponible"
        window.mostrarVentanaOffline = function () {
            const modal = document.getElementById("modalOffline");
            if (modal) {
                modal.style.display = "flex";
                setTimeout(() => {
                    modal.style.display = "none";
                }, 3000);
            }
        };
    });
</script>

</asp:Content>