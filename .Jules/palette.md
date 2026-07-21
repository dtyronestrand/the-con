## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-07-21 - Keyboard Accessibility for Custom Pseudo-Buttons
**Learning:** When building custom interactive elements that act like buttons (such as a `<p role="button">`), they are not natively keyboard focusable or triggerable. This creates a severe accessibility barrier for users navigating without a mouse.
**Action:** Ensure custom pseudo-buttons are made keyboard accessible by adding `tabindex="0"` (to include them in the tab order) and attaching handlers for both `@keydown.enter` and `@keydown.space.prevent` to trigger the intended action.
