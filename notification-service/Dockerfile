# Etapa 1: Build (Usamos la imagen grande del SDK)
FROM mcr.microsoft.com/dotnet/sdk:8.0 AS build
WORKDIR /app
COPY . .
RUN dotnet publish -c Release -o out

# Etapa 2: Runtime (Usamos la imagen pequeña "Chisel" o Alpine)
FROM mcr.microsoft.com/dotnet/aspnet:8.0
WORKDIR /app
COPY --from=build /app/out .

# Exponemos el puerto interno (Por defecto 8080 en .NET 8)
EXPOSE 8080
ENTRYPOINT ["dotnet", "NotificationService.dll"]