<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'AutoParts Hub' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen">
<div class="min-h-screen lg:flex">
    <aside class="w-full bg-slate-950 p-5 text-slate-100 lg:min-h-screen lg:w-72">
        <div class="mb-8 flex items-center gap-3"><div class="rounded-xl bg-amber-400 p-2 font-bold text-slate-950">AP</div><div><div class="font-bold">AutoParts Hub</div><div class="text-xs text-slate-400">مرجع و عملیات فروشگاه</div></div></div>
        <nav class="space-y-1 text-sm"><a class="block rounded-lg bg-slate-800 px-3 py-2" href="{{ route('dashboard') }}">داشبورد</a><span class="block px-3 pb-1 pt-5 text-xs text-slate-500">عملیات</span><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="#">فروش سریع</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="#">موجودی و انبار</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="#">خرید و تأمین</a><span class="block px-3 pb-1 pt-5 text-xs text-slate-500">بانک خودرو</span><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.makes') }}">سازندگان</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.models') }}">مدل‌ها</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.generations') }}">نسل‌ها</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.variants') }}">تیپ‌ها و نسخه‌ها</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.engines') }}">موتورها</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.transmissions') }}">گیربکس‌ها</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="{{ route('vehicles.sources') }}">منابع فنی</a><span class="block px-3 pb-1 pt-5 text-xs text-slate-500">مرجع قطعات</span><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="#">قطعات و سازگاری</a><a class="block rounded-lg px-3 py-2 hover:bg-slate-800" href="#">گزارش‌ها</a></nav>
    </aside>
    <main class="flex-1">@yield('content')</main>
</div>
</body></html>
