# استقرار

اجرای محلی: `.env` را تنظیم و `docker compose up --build` اجرا کنید؛ UI در `http://localhost:8080` است. PostgreSQL برای تست‌های host فقط روی `127.0.0.1:55432` منتشر می‌شود و Redis در شبکه Compose باقی می‌ماند. Production به HTTPS، secret manager، health check و log aggregation نیاز دارد.
