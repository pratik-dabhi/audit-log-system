
<header class="app-header">

    <div class="header-left">
        <a href="/orders" class="logo">
            Order Management
        </a>
    </div>

    <div class="header-right">

        <div class="user-info">
            <span class="user-label">Welcome,</span>

            <span class="user-name">
                {{ auth()->user()->name }}
            </span>
        </div>

        <a href="{{ route('audit-logs.index') }}" class="outline-btn green">
            Audit Logs
        </a>

        <form
            action="{{ route('logout') }}"
            method="POST"
        >
            @csrf

            <button
                type="submit"
                class="outline-btn danger"
            >
                Logout
            </button>
        </form>

    </div>

</header>

<style>
    .app-header {
        width: 100%;
        height: 64px;
        padding: 0 30px;
        margin-bottom: 15px;

        display: flex;
        align-items: center;
        justify-content: space-between;

        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;

        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
    }

    .logo {
        color: #111827;
        font-size: 20px;
        font-weight: 700;
        text-decoration: none;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .user-label {
        color: #6b7280;
        font-size: 14px;
    }

    .user-name {
        color: #111827;
        font-size: 14px;
        font-weight: 600;
    }

    .outline-btn {
        padding: 8px 14px;
        border-radius: 6px;
        background: #ffffff;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
    }

    .green{
        border: 1px solid #008000;
        color: #008000;
        text-decoration: none;
    }

    .danger{
        border: 1px solid #dc2626;
        color: #dc2626;
    }

    .danger:hover {
        background: #dc2626;
        color: #ffffff;
    }
    .green:hover {
        background: #014901;
        color: #ffffff;
    }
</style>
