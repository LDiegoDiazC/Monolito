using Microsoft.AspNetCore.Mvc;

var builder = WebApplication.CreateBuilder(args);
var app = builder.Build();

// Endpoint de Salud (Health Check para Kubernetes)
app.MapGet("/", () => "Notification Service is UP 🚀");

// Endpoint para enviar correos
app.MapPost("/send-email", ([FromBody] EmailRequest request) => 
{
    // lo que antes hacía sleep en PHP, ahora es un proceso asíncrono no bloqueante.
    Console.WriteLine($"[INFO] Enviando correo a: {request.To}...");
    
    // Aquí iría la lógica real (SMTP, SendGrid, etc.)

    Console.WriteLine($"[SUCCESS] Correo enviado orden #{request.OrderId}"); 
    return Results.Ok(new { status = "Enviado", timestamp = DateTime.UtcNow });
});

//Endpoint Async para simular un proceso largo sin bloquear el hilo principal
app.MapPost("/send-email-async", async ([FromBody] EmailRequest request) => 
{
    Console.WriteLine($"[INFO] Iniciando envío asíncrono a: {request.To}...");
    
    // Simulamos un proceso largo sin bloquear el hilo principal
    await Task.Delay(20000); // 20 segundos de espera asíncrona

    Console.WriteLine($"[SUCCESS] Correo enviado orden #{request.OrderId} de forma asíncrona"); 
    return Results.Ok(new { status = "Enviado Async", timestamp = DateTime.UtcNow });
});

app.Run();

// Modelo de datos (DTO) - Lo que vamos a recibir en el POST
record EmailRequest(string To, int OrderId, string Subject);