## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.
## 2026-04-10 - Focus Visibility on Hover-Revealed Elements
**Learning:** Relying on 'group-hover' to reveal interactive elements (like edit buttons) completely hides them from keyboard-only users who navigate via the Tab key. These elements remain invisible when focused if 'focus' or 'focus-visible' utility classes are missing.
**Action:** When using 'opacity-0 group-hover:opacity-100' patterns for actionable icons, always add 'focus:opacity-100' or equivalent styling to ensure the element becomes visible and operable when it receives keyboard focus, along with clear focus rings like 'focus-visible:ring-2'.
