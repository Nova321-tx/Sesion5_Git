<%@ Page Title="" Language="C#" MasterPageFile="~/Site.Master" AutoEventWireup="true" CodeBehind="AccesosBD.aspx.cs" Inherits="AppInteractive.AccesosBD" %>
<asp:Content ID="Content1" ContentPlaceHolderID="MainContent" runat="server">
<asp:Label ID="lblMensaje" runat="server" ForeColor="Red" Font-Bold="true"></asp:Label>
    <div id="modalEspera" class="modal">
        <div class="modal-content">
            <h2>Acerque su tarjeta para identificarla</h2>
            <p>Esperando escaneo...</p>
            <div class="button-content">
                <asp:TextBox ID="txtRFID" runat="server" AutoPostBack="true" OnTextChanged="DatosRecibidos" autofocus="true" AutoComplete="off"/>    
            </div>
        </div>
    </div>
    <asp:Panel ID="pnlDatos" runat="server" CssClass="pnlDatosStyle" Visible="false">
    <div style="display: flex; justify-content: space-around; align-items: flex-start; padding: 20px;">
        <div class="datosleft">
            <h3>Datos del Estudiante</h3>
            <p><strong>Nombre:</strong> <asp:Label ID="lblNombre" runat="server" /></p>
            <p><strong>Apellido:</strong> <asp:Label ID="lblApellido" runat="server" /></p>
            <p><strong>Carnet:</strong> <asp:Label ID="lblCarnet" runat="server" /></p>
        </div>
        <div class="datosright">
            <h3>WiFi UPDS</h3>
            <img id="QR" src="Img/QR.png" runat="server" alt="Código QR" style="max-width: 180px; margin-bottom: 2px;" />
            <p id="countdown" style="color: red; font-size: 18px; "></p>
        </div>
    </div>
    </asp:Panel>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            text-align: center;
        }
        .button-contentt{
            opacity: 0;
            position: absolute;
            pointer-events: auto;
        }
        #<%= txtRFID.ClientID %> {
            opacity: 0;
            position: absolute;
            pointer-events: auto;
        }
        .modal-content {
            color: white !important;
            background-color: #03040e;
            margin: 20% auto;
            padding: 20px;
            width: 50%;
            border-radius: 10px;
        }

        #datosEstudiante {
            margin-top: 20px;
            text-align: center;
            border: 1px solid #ccc;
            padding: 15px;
            display: none;
        }

        img {
            max-width: 150px;
            margin-top: 10px;
        }
        .pnlDatosStyle {
            background-color: #051636 !important;  /* Fondo gris claro; cámbialo si lo deseas */
            color: white !important;
            margin: 30px auto !important;           /* Margen para centrar y separar */
            padding: 30px !important;                /* Espacio interno */
            border-radius: 10px;                      /* Bordes redondeados */
            max-width: 900px;                         /* Ancho máximo */
            box-shadow: 0 0 10px rgba(0,0,0,0.1);       /* Sombra sutil */
            font-family: Arial, sans-serif;           /* Fuente para el contenido */
        }
  
        .datosContainer {
            display: flex;
            justify-content: space-around;
            align-items: flex-start;
        }
  
        .datosleft {
            flex: 1;
            padding: 10px;
            text-align: left;
        }
  
        .datosright {
            flex: 1;
            padding: 10px;
            text-align: center;
        }
        #countdown {
            color: white !important;              /* Letras blancas */
            font-size: 20px;
            font-family: monospace;     /* Fuente de ancho fijo */
            display: inline-block;      /* Se comporta como bloque para controlar dimensiones */
            width: 150px;               /* Ancho fijo para evitar cambios de tamaño al actualizar el texto */
            height: 2px;
            text-align: center;
        }
    </style>
    <script type="text/javascript">
        var isPostBack = '<%= Page.IsPostBack %>' === 'True';
    </script>
    <script>
      // Verifica que el elemento countdown existe
        const countdownElem = document.getElementById("countdown");
        console.log("Redirigiendo en 20 segundos...");
        let seconds = 20;
        countdownElem.innerText = "Redirigiendo en " + seconds + " segundos...";

        // Actualiza el temporizador cada 1 segundo
        let countdownInterval = setInterval(function () {
            seconds--;
            if (seconds > 0) {
                countdownElem.innerText = "Redirigiendo en " + seconds + " segundos...";
            } else {
                clearInterval(countdownInterval);
            }
        }, 1000);

        // Redirige después de 20 segundos
        setTimeout(function () {
            window.location.replace("Principal.aspx");
        }, 20000);
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputRFID = document.getElementById('<%= txtRFID.ClientID %>');
            const modal = document.getElementById('modalEspera');
            const datosEstudiante = document.getElementById('pnlDatos');
            const qr = document.getElementById('QR');
            const lblMensaje = document.getElementById('<%= lblMensaje.ClientID %>');

            // Mantener el foco en el inputRFID
            function mantenerFoco() {
                if (modal.style.display === 'block') {
                    inputRFID.focus();
                }
            }
            setInterval(mantenerFoco, 100);

            window.cerrarModal = function (exito) {
                modal.style.display = "none"; // Cerrar el modal

                if (exito) {
                    datosEstudiante.style.display = "block"; // Mostrar datos del estudiante
                    qr.style.display = "block"; // Mostrar el QR

                    // Iniciar contador de 20 segundos
                    let seconds = 20;
                    let countdownElem = document.getElementById("countdown");
                    countdownElem.innerText = "Redirigiendo en " + seconds + " segundos...";

                    let countdownInterval = setInterval(function () {
                        seconds--;
                        if (seconds > 0) {
                            countdownElem.innerText = "Redirigiendo en " + seconds + " segundos...";
                        } else {
                            clearInterval(countdownInterval);
                        }
                    }, 1000);

                    console.log("Redirigiendo en 20 segundos...");
                    setTimeout(function () {
                        window.location.replace("Principal.aspx");;
                    }, 20000);
                } else {
                    lblMensaje.style.display = "block"; // Mostrar mensaje de error
                }
            };

            // Mostrar modal al cargar la página
            window.onload = function () {
                if (!isPostBack) {
                    mostrarModal();
                }
            };

            function mostrarModal() {
                modal.style.display = "block";
                setTimeout(() => inputRFID.focus(), 1);
            }
        });

    </script>
</asp:Content>
