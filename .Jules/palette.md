## 2024-04-03 - Accessible Toolbars
**Learning:** Hover-only toolbars (`group-hover:opacity-100`) make interactive elements completely invisible to keyboard-only users navigating via tab. This is a severe a11y trap.
**Action:** Always add `focus-within:opacity-100` alongside `group-hover` styles for any toolbar or container with hidden interactive elements, and ensure inner buttons have visible focus rings (`focus-visible:ring-2`) and `aria-label`s.
## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
