## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-04-16 - Text Symbol Navigation Buttons Accessibility
**Learning:** When using text symbols like '-' and '+' for icon-only buttons, screen readers will read them literally ('minus', 'plus'), which may lack context or be confusing. Using just `aria-label` on the button isn't enough, as the screen reader might still read the symbol text alongside the label.
**Action:** When implementing icon-only buttons with visual text symbols, always wrap the text symbol in a `<span aria-hidden="true">`, and provide an `aria-label` on the parent `<button>` to ensure the screen reader only reads the intended action.
