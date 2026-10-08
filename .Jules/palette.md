## 2024-05-14 - Keyboard Accessibility in Interactive Elements
**Learning:** Relying solely on `group-hover:opacity-100` to show interactive elements hides them from keyboard users who cannot hover. Additionally, icon-only buttons need `aria-label` and `title` attributes to be perceivable by screen reader users and to display tooltips.
**Action:** When adding hover states that reveal actionable elements, ensure there is a corresponding `focus-within:opacity-100` state. Always use accessible names (`aria-label`, `title`) and clear `focus-visible` styling on icon-only buttons.

## 2026-10-08 - Focusability of elements that only appear on hover
**Learning:** In order for an element to be keyboard accessible, the focus style must be explicitly exposed. A `group-hover` property doesn't help a keyboard user because they can't hover to make the element appear, and `focus-visible` on its own may still be rendered invisible by the parent's `opacity-0`.
**Action:** Add `group-focus-within:opacity-100` as well as `focus-visible:opacity-100` to elements hidden using parent hovering.
