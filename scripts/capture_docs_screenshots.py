from pathlib import Path

from playwright.sync_api import BrowserContext, Page, Response, sync_playwright


BASE_URL = "http://192.168.1.100:8000"
ROOT = Path(__file__).resolve().parent.parent
DOCS_DIR = ROOT / "docs" / "screenshots"
MEMBER_DIR = DOCS_DIR / "member"
ADMIN_DIR = DOCS_DIR / "admin"


def ensure_dirs() -> None:
    MEMBER_DIR.mkdir(parents=True, exist_ok=True)
    ADMIN_DIR.mkdir(parents=True, exist_ok=True)


def wait_for_app(page: Page) -> None:
    page.wait_for_load_state("networkidle")
    page.wait_for_timeout(1200)


def is_forbidden(page: Page, response: Response | None) -> bool:
    if response and response.status == 403:
        return True

    title = page.title().strip().lower()

    if "403" in title or "forbidden" in title:
        return True

    body_text = page.locator("body").inner_text(timeout=5_000).strip().lower()

    forbidden_markers = [
        "403",
        "forbidden",
        "unauthorized",
        "this action is unauthorized",
    ]

    return any(marker in body_text for marker in forbidden_markers)


def capture(page: Page, url: str, output: Path, wait_selector: str | None = None) -> bool:
    response = page.goto(url, wait_until="domcontentloaded")
    wait_for_app(page)

    if is_forbidden(page, response):
        if output.exists():
            output.unlink()
        print(f"SKIP 403: {url}")
        return False

    if wait_selector:
        page.locator(wait_selector).first.wait_for(state="visible", timeout=15_000)
        page.wait_for_timeout(500)
    output.parent.mkdir(parents=True, exist_ok=True)
    page.screenshot(path=str(output), full_page=True)
    print(f"CAPTURED: {url} -> {output.relative_to(ROOT)}")
    return True


def login(page: Page, email: str, password: str, expected_url_part: str) -> None:
    page.goto(f"{BASE_URL}/login", wait_until="domcontentloaded")
    wait_for_app(page)
    page.fill("#email", email)
    page.fill("#password", password)
    page.get_by_role("button", name="Masuk").click()
    page.wait_for_url(f"**{expected_url_part}", timeout=20_000)
    wait_for_app(page)


def capture_member(context: BrowserContext) -> None:
    page = context.new_page()

    capture(page, f"{BASE_URL}/login", MEMBER_DIR / "login-member.png", "form")
    login(page, "member1@mail.com", "member123", "/home")

    capture(page, f"{BASE_URL}/home", MEMBER_DIR / "home-dashboard.png", "h1")
    capture(page, f"{BASE_URL}/materi", MEMBER_DIR / "materials-index.png", "h1")
    capture(
        page,
        f"{BASE_URL}/materi/fondasi-konten-youtube",
        MEMBER_DIR / "materials-detail.png",
        "#video-player",
    )
    capture(page, f"{BASE_URL}/room-zoom", MEMBER_DIR / "room-zoom.png", "#room-stage")
    capture(page, f"{BASE_URL}/rekaman-zoom", MEMBER_DIR / "zoom-records.png", "#zoom-player")

    page.close()


def capture_admin(context: BrowserContext) -> None:
    page = context.new_page()
    login(page, "admin@mail.com", "admin123", "/admin")

    admin_pages = [
        ("/admin", ADMIN_DIR / "dashboard.png", "body"),
        ("/admin/programs", ADMIN_DIR / "programs.png", "h1"),
        ("/admin/materials", ADMIN_DIR / "materials.png", "h1"),
        ("/admin/videos", ADMIN_DIR / "videos.png", "h1"),
        ("/admin/pdf-documents", ADMIN_DIR / "pdf-documents.png", "h1"),
        ("/admin/material-updates", ADMIN_DIR / "material-updates.png", "h1"),
        ("/admin/member-profiles", ADMIN_DIR / "member-profiles.png", "h1"),
        ("/admin/mentor-profiles", ADMIN_DIR / "mentor-profiles.png", "h1"),
        ("/admin/zoom-records", ADMIN_DIR / "zoom-records.png", "h1"),
        ("/admin/zoom-rooms", ADMIN_DIR / "zoom-rooms.png", "h1"),
        ("/admin/zoom-room-questions", ADMIN_DIR / "zoom-room-questions.png", "h1"),
        ("/admin/premium-payments", ADMIN_DIR / "premium-payments.png", "h1"),
        ("/admin/home-appearance-settings", ADMIN_DIR / "home-appearance-settings.png", "h1"),
    ]

    for path, output, selector in admin_pages:
        capture(page, f"{BASE_URL}{path}", output, selector)

    page.close()


def main() -> None:
    ensure_dirs()

    with sync_playwright() as playwright:
        browser = playwright.chromium.launch(headless=True)

        member_context = browser.new_context(viewport={"width": 1600, "height": 1200})
        admin_context = browser.new_context(viewport={"width": 1600, "height": 1200})

        capture_member(member_context)
        capture_admin(admin_context)

        member_context.close()
        admin_context.close()
        browser.close()


if __name__ == "__main__":
    main()
