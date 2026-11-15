<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Data Pesanan - Maybeads</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  @vite(['resources/css/main/dashboard.css'])

  <style>
    /* ==========================================================================
       RESET & BASE TYPOGRAPHY
       ========================================================================== */
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      background-color: #f8fafc;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      padding-bottom: 90px; /* Space for sticky bottom bar */
    }

    /* ==========================================================================
       TOP BAR (Brand: Maybead.s + Nav Links)
       ========================================================================== */
    .order-top-bar {
      background-color: #242c38;
      color: #ffffff;
      padding: 14px 0;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    .order-top-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .top-brand-logo {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 20px;
      font-weight: 700;
      color: #ffffff;
      text-decoration: none;
      letter-spacing: 0.3px;
    }
    .top-menu-links {
      display: flex;
      align-items: center;
      gap: 24px;
      list-style: none;
    }
    .top-menu-link {
      font-size: 11.5px;
      font-weight: 600;
      letter-spacing: 1.2px;
      color: #cbd5e1;
      text-decoration: none;
      transition: color 0.15s ease;
    }
    .top-menu-link:hover {
      color: #ffffff;
    }

    /* ==========================================================================
       SUB NAVBAR (Maybeads / Katalog Belanja + Keranjang & Akun)
       ========================================================================== */
    .order-sub-navbar {
      background: #ffffff;
      border-bottom: 1px solid #e2e8f0;
      padding: 12px 0;
    }
    .order-sub-nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .order-sub-nav-left {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 13.5px;
    }
    .sub-nav-brand {
      font-family: 'Playfair Display', Georgia, serif;
      font-size: 19px;
      font-weight: 700;
      color: #244173;
      text-decoration: none;
    }
    .sub-nav-crumb {
      color: #64748b;
      font-size: 12px;
    }
    .order-sub-nav-right {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .action-pill-btn {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      padding: 6px 14px;
      border: 1px solid #d1d5db;
      border-radius: 9999px;
      background: #ffffff;
      color: #334155;
      font-size: 12.5px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.15s ease;
    }
    .action-pill-btn:hover {
      background: #f8fafc;
      border-color: #94a3b8;
    }
    .action-pill-btn svg {
      width: 15px;
      height: 15px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2;
    }

    /* ==========================================================================
       MAIN CONTENT CONTAINER
       ========================================================================== */
    .order-main-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 24px 24px 40px;
      flex: 1;
      width: 100%;
    }

    /* Breadcrumbs & Header */
    .order-header-box {
      margin-bottom: 24px;
    }
    .order-back-breadcrumb {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      color: #64748b;
      font-size: 13px;
      text-decoration: none;
      margin-bottom: 8px;
      transition: color 0.15s ease;
    }
    .order-back-breadcrumb:hover {
      color: #244173;
    }
    .order-header-title {
      font-size: 22px;
      font-weight: 700;
      color: #0f172a;
    }

    /* Two-column layout */
    .order-content-grid {
      display: grid;
      grid-template-columns: 1fr 380px;
      gap: 24px;
      align-items: start;
    }

    /* Card Box Styles */
    .order-card {
      background: #ffffff;
      border: 1px solid #dcdfe4;
      border-radius: 12px;
      padding: 24px;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .order-section-title {
      font-size: 16px;
      font-weight: 700;
      color: #141418;
      margin-bottom: 16px;
    }

    /* ==========================================================================
       LEFT COLUMN: DAFTAR PRODUK & TOTAL HARGA
       ========================================================================== */
    .order-table-wrap {
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      overflow: hidden;
      margin-bottom: 28px;
    }

    /* Dark Slate Table Header */
    .order-table-thead {
      background-color: #2d3139;
      color: #ffffff;
      padding: 12px 20px;
      display: grid;
      grid-template-columns: 1.6fr 120px 80px 130px;
      gap: 16px;
      align-items: center;
      font-size: 13px;
      font-weight: 600;
    }
    .order-table-thead .col-unit-price {
      text-align: center;
    }
    .order-table-thead .col-qty {
      text-align: center;
    }
    .order-table-thead .col-total {
      text-align: right;
    }

    /* Table Body Rows */
    .order-table-tbody {
      background: #ffffff;
    }
    .order-item-row {
      padding: 16px 20px;
      display: grid;
      grid-template-columns: 1.6fr 120px 80px 130px;
      gap: 16px;
      align-items: center;
      border-bottom: 1px solid #f1f5f9;
      transition: background 0.15s;
    }
    .order-item-row:last-child {
      border-bottom: none;
    }
    .order-item-row:hover {
      background: #fafbfc;
    }

    .order-product-cell {
      display: flex;
      align-items: center;
      gap: 14px;
      min-width: 0;
    }
    .order-product-thumb {
      width: 54px;
      height: 54px;
      min-width: 54px;
      border-radius: 8px;
      object-fit: cover;
      background: #e2e8f0;
      border: 1px solid #e2e8f0;
    }
    .order-product-info {
      min-width: 0;
      display: flex;
      flex-direction: column;
      gap: 3px;
    }
    .order-product-name {
      font-size: 13.5px;
      font-weight: 700;
      color: #1e293b;
      line-height: 1.35;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .order-product-cat {
      font-size: 12px;
      color: #64748b;
    }

    .order-unit-price-cell {
      text-align: center;
      font-size: 13px;
      font-weight: 600;
      color: #334155;
    }
    .order-qty-cell {
      text-align: center;
      font-size: 13px;
      font-weight: 600;
      color: #475569;
    }
    .order-total-cell {
      text-align: right;
      font-size: 13.5px;
      font-weight: 700;
      color: #0f172a;
    }

    /* Breakdown Section: Total Harga */
    .order-pricing-breakdown {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .order-pricing-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 13.5px;
      color: #475569;
    }
    .order-pricing-row .pricing-val {
      font-weight: 600;
      color: #1e293b;
    }

    .order-divider-line {
      border: none;
      border-top: 1.5px solid #2e3440;
      margin: 18px 0 14px;
    }

    .order-final-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-top: 4px;
    }
    .order-final-label {
      font-size: 16px;
      font-weight: 700;
      color: #0f172a;
    }
    .order-final-val {
      font-size: 20px;
      font-weight: 800;
      color: #0f172a;
    }

    /* ==========================================================================
       RIGHT COLUMN: PILIH JENIS PENGAMBILAN & PEMBAYARAN
       ========================================================================== */
    .order-options-group {
      margin-bottom: 24px;
    }
    .order-options-group:last-child {
      margin-bottom: 0;
    }

    .option-radio-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 10px 0;
      cursor: pointer;
      user-select: none;
      transition: opacity 0.15s;
    }
    .option-radio-left {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 13.5px;
      color: #1e293b;
    }
    .custom-radio-input {
      width: 17px;
      height: 17px;
      accent-color: #18181b;
      cursor: pointer;
    }
    .option-radio-price {
      font-size: 13px;
      font-weight: 600;
      color: #334155;
    }

    /* Non-active / Disabled Option Styling */
    .option-radio-item.is-disabled {
      opacity: 0.45;
      cursor: not-allowed;
    }
    .option-radio-item.is-disabled .custom-radio-input {
      cursor: not-allowed;
    }
    .option-radio-item.is-disabled .option-radio-price {
      color: #94a3b8;
    }
    .status-badge-inactive {
      display: inline-block;
      font-size: 10.5px;
      font-weight: 700;
      padding: 2px 6px;
      border-radius: 4px;
      background: #fee2e2;
      color: #ef4444;
      margin-left: 6px;
    }

    /* Address Input Box */
    .address-input-box {
      margin-top: 10px;
      display: flex;
      align-items: center;
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 6px;
      padding: 4px 6px 4px 12px;
      gap: 8px;
    }
    .address-text-input {
      border: none;
      outline: none;
      flex: 1;
      font-size: 12.5px;
      color: #1e293b;
      font-family: inherit;
      background: transparent;
    }
    .address-text-input::placeholder {
      color: #94a3b8;
    }
    .btn-default-address {
      border: 1px solid #d1d5db;
      background: #f8fafc;
      border-radius: 4px;
      padding: 6px 12px;
      font-size: 11.5px;
      font-weight: 600;
      color: #334155;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.15s;
    }
    .btn-default-address:hover {
      background: #e2e8f0;
    }
    .address-notice-text {
      font-size: 11.5px;
      color: #94a3b8;
      margin-top: 6px;
      line-height: 1.4;
    }

    /* Group Divider */
    .order-group-divider {
      border: none;
      border-top: 1px solid #e2e8f0;
      margin: 20px 0;
    }

    /* ==========================================================================
       BOTTOM STICKY BAR (Total, Batal, Lanjut Ke Pembayaran)
       ========================================================================== */
    .order-bottom-sticky-bar {
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: #ffffff;
      border-top: 1px solid #e2e8f0;
      box-shadow: 0 -3px 12px rgba(0, 0, 0, 0.04);
      padding: 14px 0;
      z-index: 100;
    }
    .order-bottom-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 24px;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 28px;
    }
    .order-bottom-total {
      font-size: 15px;
      color: #475569;
    }
    .order-bottom-total strong {
      font-size: 18px;
      color: #0f172a;
      font-weight: 800;
      margin-left: 6px;
    }
    .order-bottom-actions {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .btn-order-cancel {
      padding: 9px 24px;
      border: 1.5px solid #1e293b;
      background: #ffffff;
      color: #1e293b;
      font-size: 13.5px;
      font-weight: 600;
      border-radius: 6px;
      text-decoration: none;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-order-cancel:hover {
      background: #f1f5f9;
    }
    .btn-order-submit {
      padding: 9px 22px;
      border: none;
      background: #244173;
      color: #ffffff;
      font-size: 13.5px;
      font-weight: 700;
      border-radius: 6px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background-color 0.15s;
      text-decoration: none;
    }
    .btn-order-submit:hover {
      background: #1b3259;
    }
    .btn-order-submit svg {
      width: 15px;
      height: 15px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2.5;
    }

    /* Empty state */
    .order-empty-box {
      text-align: center;
      padding: 50px 20px;
      color: #64748b;
    }
    .order-empty-box a {
      display: inline-block;
      margin-top: 14px;
      padding: 8px 18px;
      background: #244173;
      color: #ffffff;
      border-radius: 6px;
      text-decoration: none;
      font-weight: 600;
      font-size: 13px;
    }

    /* ==========================================================================
       RESPONSIVE
       ========================================================================== */
    @media (max-width: 900px) {
      .order-content-grid {
        grid-template-columns: 1fr;
      }
      .order-table-thead {
        display: none;
      }
      .order-item-row {
        grid-template-columns: 1fr;
        gap: 10px;
      }
      .order-unit-price-cell, .order-qty-cell, .order-total-cell {
        text-align: left;
      }
      .order-unit-price-cell::before { content: "Harga: "; font-weight: 500; color: #64748b; }
      .order-qty-cell::before { content: "Jumlah: "; font-weight: 500; color: #64748b; }
      .order-total-cell::before { content: "Total: "; font-weight: 500; color: #64748b; }
      .order-bottom-container {
        justify-content: space-between;
      }
    }

    /* ==========================================================================
       USER ACCOUNT DROPDOWN
       ========================================================================== */
    .user-account-dropdown {
      position: absolute;
      top: calc(100% + 8px);
      right: 0;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 220px;
      z-index: 100;
      padding: 6px 0;
      animation: modalZoomIn 0.15s ease-out;
    }
    .acc-dropdown-header {
      padding: 10px 14px;
      font-size: 12px;
    }
    .acc-dropdown-header strong { display: block; color: #0f172a; font-size: 13px; }
    .acc-dropdown-header small { color: #64748b; font-size: 11px; }
    .acc-dropdown-divider { height: 1px; background: #f1f5f9; margin: 4px 0; }
    .acc-dropdown-item {
      display: flex;
      align-items: center;
      gap: 8px;
      padding: 8px 14px;
      font-size: 12.5px;
      font-weight: 500;
      color: #334155;
      text-decoration: none;
      background: none;
      border: none;
      width: 100%;
      text-align: left;
      cursor: pointer;
      transition: background 0.15s;
    }
    .acc-dropdown-item:hover {
      background: #f8fafc;
      color: #0f172a;
    }
    .acc-dropdown-item.text-danger {
      color: #dc2626;
    }
    .acc-dropdown-item.text-danger:hover {
      background: #fef2f2;
    }

    /* ==========================================================================
       POPUP KONFIRMASI PESANAN & INSTRUKSI PEMBAYARAN
       ========================================================================== */
    .checkout-modal-backdrop {
      position: fixed;
      inset: 0;
      background-color: rgba(15, 23, 42, 0.6);
      backdrop-filter: blur(4px);
      z-index: 99999;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .checkout-modal-card {
      background: #ffffff;
      border-radius: 14px;
      width: 100%;
      max-width: 520px;
      box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.2);
      overflow: hidden;
      animation: modalZoomIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      display: flex;
      flex-direction: column;
      max-height: 90vh;
    }
    @keyframes modalZoomIn {
      from { opacity: 0; transform: scale(0.95) translateY(-8px); }
      to   { opacity: 1; transform: scale(1) translateY(0); }
    }
    .checkout-modal-header {
      padding: 18px 24px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .checkout-modal-title {
      font-size: 17px;
      font-weight: 800;
      color: #0f172a;
    }
    .checkout-modal-subtitle {
      font-size: 12px;
      color: #64748b;
      margin-top: 2px;
    }
    .btn-checkout-modal-close {
      background: none;
      border: none;
      font-size: 24px;
      color: #94a3b8;
      cursor: pointer;
      line-height: 1;
      padding: 4px;
      border-radius: 6px;
      transition: all 0.15s;
    }
    .btn-checkout-modal-close:hover {
      color: #0f172a;
      background: #f1f5f9;
    }
    .checkout-modal-body {
      padding: 20px 24px;
      overflow-y: auto;
      display: flex;
      flex-direction: column;
      gap: 14px;
    }
    .confirm-section-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 8px;
    }
    .confirm-section-title {
      font-size: 12px;
      font-weight: 700;
      color: #475569;
      text-transform: uppercase;
      letter-spacing: 0.04em;
    }
    .confirm-item-count {
      font-size: 11px;
      font-weight: 700;
      color: #244173;
      background: #eff6ff;
      padding: 2px 8px;
      border-radius: 999px;
      border: 1px solid #bfdbfe;
    }
    .confirm-items-scroll-list {
      display: flex;
      flex-direction: column;
      gap: 8px;
      max-height: 150px;
      overflow-y: auto;
      border: 1px solid #f1f5f9;
      background: #f8fafc;
      padding: 10px;
      border-radius: 8px;
    }
    .confirm-item-entry {
      display: flex;
      align-items: center;
      justify-content: space-between;
      font-size: 12.5px;
      gap: 10px;
    }
    .confirm-item-left {
      display: flex;
      align-items: center;
      gap: 8px;
      overflow: hidden;
    }
    .confirm-item-thumb {
      width: 28px;
      height: 28px;
      border-radius: 6px;
      object-fit: cover;
      flex-shrink: 0;
      border: 1px solid #e2e8f0;
    }
    .confirm-item-name {
      font-weight: 600;
      color: #1e293b;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 220px;
    }
    .confirm-item-qty {
      color: #64748b;
      font-size: 11px;
    }
    .confirm-item-total {
      font-weight: 700;
      color: #0f172a;
      white-space: nowrap;
    }
    .confirm-info-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 10px 14px;
    }
    .confirm-info-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      font-size: 12.5px;
    }
    .confirm-info-label {
      display: flex;
      align-items: center;
      gap: 6px;
      color: #64748b;
      font-weight: 600;
    }
    .confirm-info-label svg {
      width: 15px;
      height: 15px;
    }
    .confirm-info-val {
      color: #0f172a;
      font-weight: 700;
    }
    .confirm-info-sub {
      font-size: 11.5px;
      color: #64748b;
      margin-top: 4px;
      padding-top: 4px;
      border-top: 1px dashed #e2e8f0;
    }
    .confirm-total-box {
      background: #eff6ff;
      border: 1px solid #bfdbfe;
      border-radius: 10px;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    .confirm-total-label {
      font-size: 13.5px;
      font-weight: 700;
      color: #1e3a8a;
    }
    .confirm-total-amount {
      font-size: 19px;
      font-weight: 800;
      color: #1e3a8a;
    }
    .confirm-notice-note {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 11.5px;
      color: #64748b;
      line-height: 1.4;
    }
    .confirm-notice-note svg {
      width: 15px;
      height: 15px;
      flex-shrink: 0;
      color: #2563eb;
      margin-top: 2px;
    }
    .checkout-modal-footer {
      padding: 14px 24px;
      background: #f8fafc;
      border-top: 1px solid #f1f5f9;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 10px;
    }
    .btn-checkout-secondary {
      padding: 9px 18px;
      border: 1px solid #cbd5e1;
      border-radius: 8px;
      background: #ffffff;
      color: #475569;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      text-decoration: none;
      transition: all 0.15s;
    }
    .btn-checkout-secondary:hover {
      background: #f1f5f9;
      border-color: #94a3b8;
    }
    .btn-checkout-primary {
      padding: 9px 20px;
      border: none;
      border-radius: 8px;
      background: #244173;
      color: #ffffff;
      font-size: 13px;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
      transition: background 0.15s;
    }
    .btn-checkout-primary:hover {
      background: #1b3259;
    }
    .btn-checkout-primary svg {
      width: 15px;
      height: 15px;
      stroke: currentColor;
      fill: none;
      stroke-width: 2.2;
    }
    .instruction-header-row {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .instruction-icon-check {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #dcfce7;
      color: #16a34a;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .instruction-icon-check svg {
      width: 20px;
      height: 20px;
    }
    .qris-display-box {
      text-align: center;
      padding: 16px;
      background: #f8fafc;
      border: 1px dashed #cbd5e1;
      border-radius: 10px;
    }
    .qris-qr-img {
      width: 160px;
      height: 160px;
      margin: 0 auto 10px;
      display: block;
      border-radius: 8px;
      background: #ffffff;
      padding: 8px;
      border: 1px solid #e2e8f0;
    }
    .bank-acc-display {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 16px;
    }
    .bank-acc-row {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 8px;
      font-size: 13px;
    }
    .btn-copy-acc {
      padding: 3px 8px;
      font-size: 11px;
      border: 1px solid #cbd5e1;
      background: #fff;
      border-radius: 4px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <!-- Top bar (Dark Slate Header - Maybead.s) -->
  <header class="order-top-bar">
    <div class="order-top-container">
      <div class="order-top-left">
        <a href="{{ route('home') }}" class="top-brand-logo">
          Maybead.s
        </a>
      </div>

      <nav class="order-top-right" aria-label="Navigasi Utama">
        <ul class="top-menu-links">
          <li><a href="{{ route('home') }}" class="top-menu-link">BERANDA</a></li>
          <li><a href="{{ route('home') }}#kontak" class="top-menu-link">KONTAK</a></li>
          <li><a href="{{ route('main.dashboard') }}" class="top-menu-link">PRODUK</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Sub-Navbar Bar -->
  <nav class="order-sub-navbar">
    <div class="order-sub-nav-container">
      <div class="order-sub-nav-left">
        <a href="{{ route('main.dashboard') }}" class="sub-nav-brand">
          Maybeads
        </a>
        <span class="sub-nav-crumb">/ Katalog Belanja</span>
      </div>

      <div class="order-sub-nav-right">
        <a href="{{ route('cart.index') }}" class="action-pill-btn" title="Lihat Keranjang Belanja">
          <svg viewBox="0 0 24 24">
            <circle cx="9" cy="21" r="1"></circle>
            <circle cx="20" cy="21" r="1"></circle>
            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
          </svg>
          <span>Keranjang</span>
        </a>

        <!-- Akun Dropdown -->
        <div class="user-account-menu-wrap" style="position: relative;">
          <button type="button" class="action-pill-btn" id="btnUserAccountToggle" title="Akun Saya">
            <svg viewBox="0 0 24 24">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
              <circle cx="12" cy="7" r="4"></circle>
            </svg>
            <span>{{ Auth::check() ? Auth::user()->name : 'Akun' }}</span>
            <svg viewBox="0 0 24 24" style="width: 12px; height: 12px;"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>

          <div class="user-account-dropdown" id="userAccountDropdown" style="display: none;">
            @auth
              <div class="acc-dropdown-header">
                <strong>{{ Auth::user()->name }}</strong>
                <small>{{ Auth::user()->email }}</small>
              </div>
              <div class="acc-dropdown-divider"></div>
              <a href="{{ route('main.dashboard') }}" class="acc-dropdown-item">Katalog Produk</a>
              <a href="{{ route('cart.index') }}" class="acc-dropdown-item">Keranjang Belanja</a>
              @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="acc-dropdown-item">Admin Dashboard</a>
              @endif
              <div class="acc-dropdown-divider"></div>
              <form method="POST" action="{{ route('logout') }}" id="orderPageLogoutForm" style="margin: 0;">
                @csrf
                <button type="submit" class="acc-dropdown-item text-danger" id="btnOrderPageLogout">
                  <svg viewBox="0 0 24 24" style="width: 14px; height: 14px; stroke: currentColor; fill: none; stroke-width: 2;">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                  </svg>
                  <span>Keluar Akun</span>
                </button>
              </form>
            @else
              <a href="{{ route('login') }}" class="acc-dropdown-item">Masuk ke Akun</a>
              <a href="{{ route('register') }}" class="acc-dropdown-item">Daftar Akun Baru</a>
            @endauth
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="order-main-container">
    <!-- Breadcrumb & Page Title -->
    <div class="order-header-box">
      <a href="{{ route('cart.index') }}" class="order-back-breadcrumb">
        <span>&lsaquo;</span>
        <span>Dashboard / Keranjang</span>
      </a>
      <h1 class="order-header-title">Detail Data Pesanan</h1>
    </div>

    <!-- 2 Column Grid Content -->
    <div class="order-content-grid">

      <!-- LEFT CARD: Daftar Produk & Total Harga -->
      <div class="order-card order-card-left">
        <h2 class="order-section-title">Daftar Produk</h2>

        <!-- Table Container -->
        <div class="order-table-wrap">
          <div class="order-table-thead">
            <div>Nama Produk</div>
            <div class="col-unit-price">Harga Satuan</div>
            <div class="col-qty">Jumlah</div>
            <div class="col-total">Total</div>
          </div>

          <div class="order-table-tbody" id="orderItemsContainer">
            <!-- Dynamically populated via JavaScript based on checked cart items -->
          </div>
        </div>

        <!-- Section: Total Harga -->
        <h2 class="order-section-title">Total Harga</h2>
        <div class="order-pricing-breakdown">
          <div class="order-pricing-row">
            <span>Subtotal</span>
            <span class="pricing-val" id="textSubtotal">Rp. 0</span>
          </div>
          <div class="order-pricing-row">
            <span>Biaya Pengantaran</span>
            <span class="pricing-val" id="textDeliveryFee">Rp. 0</span>
          </div>
          <div class="order-pricing-row">
            <span>Biaya Admin</span>
            <span class="pricing-val" id="textAdminFee">Rp. 500</span>
          </div>
          <div class="order-pricing-row">
            <span>Pengambilan</span>
            <span class="pricing-val" id="textPickupFee">Rp. 200</span>
          </div>
        </div>

        <hr class="order-divider-line">

        <!-- Final Price Row -->
        <div class="order-final-row">
          <span class="order-final-label">Harga Akhir</span>
          <span class="order-final-val" id="textGrandTotal">0,00</span>
        </div>
      </div>

      <!-- RIGHT CARD: Pilih Jenis Pengambilan & Pembayaran -->
      <div class="order-card order-card-right">
        <!-- 1. Pilih Jenis Pengambilan (Dinamis dari Database DeliveryMethod) -->
        <div class="order-options-group">
          <h3 class="order-section-title" style="font-size: 15px; margin-bottom: 12px;">Pilih Jenis Pengambilan:</h3>

          @php
            $firstActiveDeliveryFound = false;
          @endphp

          @forelse ($deliveryMethods as $delMethod)
            @php
              $isDeliveryActive = $delMethod->status === 'active';
              $isDeliveryChecked = false;
              if ($isDeliveryActive && !$firstActiveDeliveryFound) {
                  $isDeliveryChecked = true;
                  $firstActiveDeliveryFound = true;
              }
            @endphp
            <label class="option-radio-item {{ !$isDeliveryActive ? 'is-disabled' : '' }}"
                   id="labelOptionDelivery_{{ $delMethod->id }}"
                   title="{{ !$isDeliveryActive ? 'Layanan ' . $delMethod->name . ' sedang tidak aktif' : '' }}">
              <div class="option-radio-left">
                <input type="radio"
                       name="delivery_method"
                       value="{{ $delMethod->code ?: $delMethod->id }}"
                       data-id="{{ $delMethod->id }}"
                       data-price="{{ $delMethod->price }}"
                       data-name="{{ $delMethod->name }}"
                       class="custom-radio-input"
                       id="radioDelivery_{{ $delMethod->id }}"
                       {{ $isDeliveryChecked ? 'checked' : '' }}
                       {{ !$isDeliveryActive ? 'disabled' : '' }}>
                <span>{{ $delMethod->name }}</span>
                @if (!$isDeliveryActive)
                  <span class="status-badge-inactive">Tidak Aktif</span>
                @endif
              </div>
              <span class="option-radio-price">
                @if ($delMethod->code === 'delivery' && !$isDeliveryActive)
                  <span style="font-style: italic;">Menghitung...</span>
                @elseif ($delMethod->price > 0)
                  + Rp {{ number_format($delMethod->price, 0, ',', '.') }}
                @else
                  + Rp 0
                @endif
              </span>
            </label>
          @empty
            <p style="font-size: 12px; color: #94a3b8;">Tidak ada metode pengambilan tersedia.</p>
          @endforelse

          <!-- Address box (active for delivery or custom address note) -->
          <div class="address-input-box" id="addressBoxWrap">
            <input type="text"
                   class="address-text-input"
                   id="inputCustomerAddress"
                   placeholder="Isi Alamat..."
                   value="{{ $user->address ?? 'Store Maybeads Banjarbaru' }}">
            <button type="button" class="btn-default-address" id="btnDefaultAddress">Alamat Default</button>
          </div>
          <p class="address-notice-text">
            * Pengaturan metode pengambilan tersinkronisasi otomatis dengan database admin. Layanan berstatus <strong>Tidak Aktif</strong> tidak dapat dipilih oleh pelanggan.
          </p>
        </div>

        <hr class="order-group-divider">

        <!-- 2. Pilih Jenis Pembayaran (Dinamis dari Database PaymentMethod) -->
        <div class="order-options-group">
          <h3 class="order-section-title" style="font-size: 15px; margin-bottom: 12px;">Pilih Jenis Pembayaran</h3>

          @php
            $firstActivePayFound = false;
          @endphp

          @forelse ($paymentMethods as $payMethod)
            @php
              $isPayActive = $payMethod->status === 'active';
              $isPayChecked = false;
              if ($isPayActive && !$firstActivePayFound) {
                  $isPayChecked = true;
                  $firstActivePayFound = true;
              }
            @endphp
            <label class="option-radio-item {{ !$isPayActive ? 'is-disabled' : '' }}"
                   id="labelOptionPay_{{ $payMethod->id }}"
                   title="{{ !$isPayActive ? 'Metode pembayaran ' . $payMethod->name . ' sedang tidak aktif' : '' }}">
              <div class="option-radio-left">
                <input type="radio"
                       name="payment_method"
                       value="{{ $payMethod->code ?: $payMethod->id }}"
                       data-id="{{ $payMethod->id }}"
                       data-price="{{ $payMethod->price }}"
                       data-name="{{ $payMethod->name }}"
                       class="custom-radio-input"
                       id="radioPay_{{ $payMethod->id }}"
                       {{ $isPayChecked ? 'checked' : '' }}
                       {{ !$isPayActive ? 'disabled' : '' }}>
                <span>{{ $payMethod->name }}</span>
                @if (!$isPayActive)
                  <span class="status-badge-inactive">Tidak Aktif</span>
                @endif
              </div>
              <span class="option-radio-price">
                @if ($payMethod->price > 0)
                  + Rp {{ number_format($payMethod->price, 0, ',', '.') }}
                @else
                  + Rp 0
                @endif
              </span>
            </label>
          @empty
            <p style="font-size: 12px; color: #94a3b8;">Tidak ada metode pembayaran terdaftar.</p>
          @endforelse
        </div>
      </div>

    </div>
  </main>

  <!-- Sticky Bottom Checkout Bar -->
  <footer class="order-bottom-sticky-bar">
    <div class="order-bottom-container">
      <div class="order-bottom-total">
        <span>Total</span>
        <strong id="bottomTotalText">Rp. 0</strong>
      </div>

      <div class="order-bottom-actions">
        <a href="{{ route('cart.index') }}" class="btn-order-cancel">Batal</a>
        <button type="button" class="btn-order-submit" id="btnProceedPayment">
          <span>Lanjut Ke Pembayaran</span>
          <svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </button>
      </div>
    </div>
  </footer>

  <!-- Order Confirmation Modal -->
  <div class="checkout-modal-backdrop" id="orderConfirmModal" style="display: none;">
    <div class="checkout-modal-card">
      <div class="checkout-modal-header">
        <div>
          <div class="checkout-modal-title">Konfirmasi Pesanan</div>
          <div class="checkout-modal-subtitle">Periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran</div>
        </div>
        <button type="button" class="btn-checkout-modal-close" id="btnCloseOrderConfirmModal" aria-label="Tutup">&times;</button>
      </div>

      <div class="checkout-modal-body">
        <!-- List Produk Ringkas -->
        <div>
          <div class="confirm-section-head">
            <span class="confirm-section-title">Produk Dipesan</span>
            <span class="confirm-item-count" id="confirmItemsCountBadge">0 Produk</span>
          </div>
          <div class="confirm-items-scroll-list" id="confirmItemsList">
            <!-- Diisi dinamis via JS -->
          </div>
        </div>

        <!-- Detail Pengambilan & Alamat -->
        <div class="confirm-info-box">
          <div class="confirm-info-row">
            <span class="confirm-info-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
              Metode Pengambilan
            </span>
            <span class="confirm-info-val" id="confirmDeliveryMethodText">Ambil Ditempat</span>
          </div>
          <div class="confirm-info-sub" id="confirmDeliveryAddressText">
            Alamat: Store Maybeads Banjarbaru
          </div>
        </div>

        <!-- Detail Metode Pembayaran -->
        <div class="confirm-info-box">
          <div class="confirm-info-row">
            <span class="confirm-info-label">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
              Metode Pembayaran
            </span>
            <span class="confirm-info-val" id="confirmPaymentMethodText">QRIS</span>
          </div>
          <div class="confirm-info-sub" id="confirmAdminFeeText">
            Biaya Admin: Rp 500
          </div>
        </div>

        <!-- Total Pembayaran Akhir -->
        <div class="confirm-total-box">
          <span class="confirm-total-label">Total Pembayaran:</span>
          <span class="confirm-total-amount" id="confirmGrandTotalText">Rp 0</span>
        </div>

        <div class="confirm-notice-note">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
          <span>Pastikan informasi pesanan sudah sesuai sebelum melanjutkan proses pembayaran.</span>
        </div>
      </div>

      <div class="checkout-modal-footer">
        <button type="button" class="btn-checkout-secondary" id="btnCancelOrderConfirm">Periksa Lagi</button>
        <button type="button" class="btn-checkout-primary" id="btnProceedToPaymentFinal">
          <span>Ya, Lanjut Bayar</span>
          <svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"></polyline></svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Payment Instruction / Success Modal -->
  <div class="checkout-modal-backdrop" id="paymentInstructionModal" style="display: none;">
    <div class="checkout-modal-card">
      <div class="checkout-modal-header">
        <div class="instruction-header-row">
          <div class="instruction-icon-check">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"></polyline></svg>
          </div>
          <div>
            <div class="checkout-modal-title">Instruksi Pembayaran</div>
            <div class="checkout-modal-subtitle" id="instructionOrderIdText">Pesanan #MBDS-XXXXXX berhasil dibuat</div>
          </div>
        </div>
        <button type="button" class="btn-checkout-modal-close" id="btnCloseInstructionModal" aria-label="Tutup">&times;</button>
      </div>

      <div class="checkout-modal-body" id="instructionContentArea">
        <!-- Rendered dynamically: QRIS scan or Bank Transfer / E-Wallet info -->
      </div>

      <div class="checkout-modal-footer">
        <a href="{{ route('main.dashboard') }}" class="btn-checkout-secondary">Kembali ke Beranda</a>
        <a href="{{ route('cart.index') }}" class="btn-checkout-primary">
          <span>Lihat Riwayat Pesanan</span>
          <svg viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
      </div>
    </div>
  </div>

  <!-- Interactive Script -->
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // 0. Dropdown User Akun Toggle
      const btnUserAccountToggle = document.getElementById('btnUserAccountToggle');
      const userAccountDropdown = document.getElementById('userAccountDropdown');
      if (btnUserAccountToggle && userAccountDropdown) {
        btnUserAccountToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          const isShown = userAccountDropdown.style.display === 'block';
          userAccountDropdown.style.display = isShown ? 'none' : 'block';
        });

        document.addEventListener('click', (e) => {
          if (!userAccountDropdown.contains(e.target) && !btnUserAccountToggle.contains(e.target)) {
            userAccountDropdown.style.display = 'none';
          }
        });
      }

      // 1. Ambil data keranjang dari localStorage
      const defaultSampleItems = [
        {
          id: 101,
          name: 'Deskripsi Nama Produk',
          color: 'Aksesoris Cantik',
          qty: 1,
          price: 'Rp 42.000',
          rawPrice: 42000,
          image: '/images/products/sample-hub.jpg',
          checked: true
        },
        {
          id: 102,
          name: 'Deskripsi Nama Produk',
          color: 'Aksesoris Cantik',
          qty: 1,
          price: 'Rp 42.000',
          rawPrice: 42000,
          image: '/images/products/sample-hub.jpg',
          checked: true
        },
        {
          id: 103,
          name: 'Deskripsi Nama Produk',
          color: 'Aksesoris Cantik',
          qty: 1,
          price: 'Rp 42.000',
          rawPrice: 42000,
          image: '/images/products/sample-hub.jpg',
          checked: true
        }
      ];

      let rawCart = localStorage.getItem('maybeads_cart');
      let cart = rawCart !== null ? JSON.parse(rawCart) : defaultSampleItems;

      // Filter hanya produk yang dicentang di keranjang
      let checkoutItems = cart.filter(it => it.checked !== false);

      // Jika tidak ada yang dicentang tapi keranjang ada isinya, ambil semua
      if (checkoutItems.length === 0 && cart.length > 0) {
        checkoutItems = cart;
      }

      const container = document.getElementById('orderItemsContainer');
      const textSubtotal = document.getElementById('textSubtotal');
      const textDeliveryFee = document.getElementById('textDeliveryFee');
      const textAdminFee = document.getElementById('textAdminFee');
      const textPickupFee = document.getElementById('textPickupFee');
      const textGrandTotal = document.getElementById('textGrandTotal');
      const bottomTotalText = document.getElementById('bottomTotalText');
      const btnProceedPayment = document.getElementById('btnProceedPayment');
      const btnDefaultAddress = document.getElementById('btnDefaultAddress');
      const inputCustomerAddress = document.getElementById('inputCustomerAddress');

      // Modals elements
      const orderConfirmModal = document.getElementById('orderConfirmModal');
      const paymentInstructionModal = document.getElementById('paymentInstructionModal');
      const btnCloseOrderConfirmModal = document.getElementById('btnCloseOrderConfirmModal');
      const btnCancelOrderConfirm = document.getElementById('btnCancelOrderConfirm');
      const btnProceedToPaymentFinal = document.getElementById('btnProceedToPaymentFinal');
      const btnCloseInstructionModal = document.getElementById('btnCloseInstructionModal');

      const confirmItemsList = document.getElementById('confirmItemsList');
      const confirmItemsCountBadge = document.getElementById('confirmItemsCountBadge');
      const confirmDeliveryMethodText = document.getElementById('confirmDeliveryMethodText');
      const confirmDeliveryAddressText = document.getElementById('confirmDeliveryAddressText');
      const confirmPaymentMethodText = document.getElementById('confirmPaymentMethodText');
      const confirmAdminFeeText = document.getElementById('confirmAdminFeeText');
      const confirmGrandTotalText = document.getElementById('confirmGrandTotalText');
      const instructionOrderIdText = document.getElementById('instructionOrderIdText');
      const instructionContentArea = document.getElementById('instructionContentArea');

      // Default user address
      const userDefaultAddress = "{{ $user->address ?? 'Store Maybeads Banjarbaru, Jl. Mawar Indah No. 12' }}";

      if (btnDefaultAddress && inputCustomerAddress) {
        btnDefaultAddress.addEventListener('click', () => {
          inputCustomerAddress.value = userDefaultAddress;
        });
      }

      // Cegah klik pada opsi non-aktif (Amankan perilaku UI)
      document.querySelectorAll('.option-radio-item.is-disabled').forEach(item => {
        item.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          const radio = item.querySelector('input[type="radio"]');
          if (radio) radio.checked = false;
        });
      });

      // Render Item Table Rows
      if (!checkoutItems || checkoutItems.length === 0) {
        container.innerHTML = `
          <div class="order-empty-box">
            <p>Tidak ada produk yang dipilih untuk checkout.</p>
            <a href="{{ route('cart.index') }}">Kembali ke Keranjang</a>
          </div>
        `;
        return;
      }

      container.innerHTML = checkoutItems.map(item => {
        const rawPrice = Number(item.rawPrice) || 42000;
        const qty = parseInt(item.qty, 10) || 1;
        const lineTotal = rawPrice * qty;
        const displayUnitPrice = 'Rp ' + rawPrice.toLocaleString('id-ID') + ',00';
        const displayLineTotal = 'Rp ' + lineTotal.toLocaleString('id-ID') + ',00';
        const imgSrc = item.image || '/images/products/sample-hub.jpg';
        const variantText = item.color || 'Aksesoris Cantik';

        return `
          <div class="order-item-row">
            <div class="order-product-cell">
              <img src="${imgSrc}" alt="${item.name}" class="order-product-thumb">
              <div class="order-product-info">
                <span class="order-product-name" title="${item.name}">${item.name}</span>
                <span class="order-product-cat">${variantText}</span>
              </div>
            </div>
            <div class="order-unit-price-cell">
              ${displayUnitPrice}
            </div>
            <div class="order-qty-cell">
              x ${qty}
            </div>
            <div class="order-total-cell">
              ${displayLineTotal}
            </div>
          </div>
        `;
      }).join('');

      // Perhitungan Harga Dinamis
      function calculateSummary() {
        const subtotal = checkoutItems.reduce((acc, it) => {
          const unit = Number(it.rawPrice) || 42000;
          const q = parseInt(it.qty, 10) || 1;
          return acc + (unit * q);
        }, 0);

        // Ambil biaya metode pengiriman aktif
        const selectedDeliveryRadio = document.querySelector('input[name="delivery_method"]:checked');
        let deliveryFee = 0;
        let pickupFee = 0;

        if (selectedDeliveryRadio) {
          const dPrice = parseInt(selectedDeliveryRadio.dataset.price, 10) || 0;
          const val = (selectedDeliveryRadio.value || '').toLowerCase();
          if (val.includes('pickup') || val.includes('ambil')) {
            pickupFee = dPrice;
            deliveryFee = 0;
          } else {
            deliveryFee = dPrice;
            pickupFee = 0;
          }
        }

        // Ambil biaya metode pembayaran aktif
        const selectedPaymentRadio = document.querySelector('input[name="payment_method"]:checked');
        let adminFee = 0;
        if (selectedPaymentRadio) {
          adminFee = parseInt(selectedPaymentRadio.dataset.price, 10) || 0;
        }

        const grandTotal = subtotal + deliveryFee + pickupFee + adminFee;

        if (textSubtotal) textSubtotal.textContent = 'Rp. ' + subtotal.toLocaleString('id-ID') + ',00';
        if (textDeliveryFee) textDeliveryFee.textContent = deliveryFee > 0 ? ('Rp. ' + deliveryFee.toLocaleString('id-ID') + ',00') : 'Rp. 0';
        if (textAdminFee) textAdminFee.textContent = 'Rp. ' + adminFee.toLocaleString('id-ID');
        if (textPickupFee) textPickupFee.textContent = 'Rp. ' + pickupFee.toLocaleString('id-ID');

        // Format persis seperti mockup di gambar: 42.000,00 atau Rp. 48.000
        if (textGrandTotal) textGrandTotal.textContent = grandTotal.toLocaleString('id-ID') + ',00';
        if (bottomTotalText) bottomTotalText.textContent = 'Rp. ' + grandTotal.toLocaleString('id-ID');
      }

      // Event listener saat radio aktif berubah
      document.querySelectorAll('input[name="delivery_method"], input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', calculateSummary);
      });

      calculateSummary();

      // Modal helpers
      function closeConfirmModal() {
        if (orderConfirmModal) orderConfirmModal.style.display = 'none';
      }

      if (btnCloseOrderConfirmModal) btnCloseOrderConfirmModal.addEventListener('click', closeConfirmModal);
      if (btnCancelOrderConfirm) btnCancelOrderConfirm.addEventListener('click', closeConfirmModal);

      if (orderConfirmModal) {
        orderConfirmModal.addEventListener('click', (e) => {
          if (e.target === orderConfirmModal) closeConfirmModal();
        });
      }

      if (btnCloseInstructionModal) {
        btnCloseInstructionModal.addEventListener('click', () => {
          if (paymentInstructionModal) paymentInstructionModal.style.display = 'none';
        });
      }
      if (paymentInstructionModal) {
        paymentInstructionModal.addEventListener('click', (e) => {
          if (e.target === paymentInstructionModal) paymentInstructionModal.style.display = 'none';
        });
      }

      // Tombol Lanjut Ke Pembayaran -> Munculkan Pop up Konfirmasi
      if (btnProceedPayment) {
        btnProceedPayment.addEventListener('click', () => {
          if (!checkoutItems || checkoutItems.length === 0) {
            alert('Keranjang Anda kosong atau belum ada produk yang dipilih.');
            return;
          }

          // Populate list ringkas produk
          if (confirmItemsCountBadge) {
            confirmItemsCountBadge.textContent = `${checkoutItems.length} Produk`;
          }
          if (confirmItemsList) {
            confirmItemsList.innerHTML = checkoutItems.map(it => {
              const rawP = Number(it.rawPrice) || 42000;
              const q = parseInt(it.qty, 10) || 1;
              const sub = rawP * q;
              return `
                <div class="confirm-item-entry">
                  <div class="confirm-item-left">
                    <img src="${it.image || '/images/products/sample-hub.jpg'}" class="confirm-item-thumb" alt="${it.name}">
                    <span class="confirm-item-name">${it.name}</span>
                    <span class="confirm-item-qty">x${q}</span>
                  </div>
                  <span class="confirm-item-total">Rp ${sub.toLocaleString('id-ID')}</span>
                </div>
              `;
            }).join('');
          }

          // Detail Pengambilan
          const selectedDelivery = document.querySelector('input[name="delivery_method"]:checked');
          const deliveryName = selectedDelivery ? (selectedDelivery.dataset.name || selectedDelivery.value) : 'Ambil Ditempat';
          if (confirmDeliveryMethodText) confirmDeliveryMethodText.textContent = deliveryName;

          const addressVal = (inputCustomerAddress && inputCustomerAddress.value.trim()) ? inputCustomerAddress.value.trim() : userDefaultAddress;
          if (confirmDeliveryAddressText) confirmDeliveryAddressText.textContent = 'Alamat / Lokasi: ' + addressVal;

          // Detail Pembayaran
          const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
          const paymentName = selectedPayment ? (selectedPayment.dataset.name || selectedPayment.value.toUpperCase()) : 'QRIS';
          const pFee = selectedPayment ? (parseInt(selectedPayment.dataset.price, 10) || 0) : 0;
          if (confirmPaymentMethodText) confirmPaymentMethodText.textContent = paymentName;
          if (confirmAdminFeeText) confirmAdminFeeText.textContent = 'Biaya Admin: Rp ' + pFee.toLocaleString('id-ID');

          // Total Akhir
          if (confirmGrandTotalText && bottomTotalText) {
            confirmGrandTotalText.textContent = bottomTotalText.textContent;
          }

          // Tampilkan Modal Konfirmasi
          if (orderConfirmModal) {
            orderConfirmModal.style.display = 'flex';
          }
        });
      }

      // Tombol "Ya, Lanjut Bayar" di Modal Konfirmasi
      if (btnProceedToPaymentFinal) {
        btnProceedToPaymentFinal.addEventListener('click', () => {
          closeConfirmModal();

          const randomCode = 'MBDS-' + Math.floor(100000 + Math.random() * 900000);
          if (instructionOrderIdText) {
            instructionOrderIdText.textContent = `Pesanan #${randomCode} berhasil dibuat`;
          }

          const selectedPayment = document.querySelector('input[name="payment_method"]:checked');
          const paymentVal = (selectedPayment ? selectedPayment.value : 'qris').toLowerCase();
          const paymentName = selectedPayment ? (selectedPayment.dataset.name || selectedPayment.value.toUpperCase()) : 'QRIS';
          const totalStr = bottomTotalText ? bottomTotalText.textContent : 'Rp 0';

          let instructionHTML = '';
          if (paymentVal.includes('qris')) {
            instructionHTML = `
              <div class="confirm-total-box" style="margin-bottom: 12px;">
                <span class="confirm-total-label">Total Tagihan:</span>
                <span class="confirm-total-amount">${totalStr}</span>
              </div>
              <div class="qris-display-box">
                <p style="font-weight: 700; color: #1e293b; font-size: 14px; margin-bottom: 4px;">Scan QRIS Maybeads</p>
                <p style="font-size: 11.5px; color: #64748b; margin-bottom: 12px;">Buka aplikasi e-wallet / mobile banking Anda (BCA, GoPay, OVO, Dana, ShopeePay) dan scan QR di bawah ini.</p>
                <img src="/images/qris-sample.png" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=MAYBEADS-${randomCode}'" alt="QRIS Code" class="qris-qr-img">
                <span style="display: inline-block; font-size: 12px; font-weight: 700; color: #0f172a; background: #e2e8f0; padding: 4px 10px; border-radius: 6px;">NMID: ID102008392819</span>
              </div>
              <div class="confirm-notice-note" style="margin-top: 10px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                <span>Selesaikan pembayaran dalam <b>15:00 menit</b>. Konfirmasi pembayaran akan diverifikasi secara otomatis.</span>
              </div>
            `;
          } else if (paymentVal.includes('bank') || paymentVal.includes('transfer')) {
            instructionHTML = `
              <div class="confirm-total-box" style="margin-bottom: 12px;">
                <span class="confirm-total-label">Total Transfer:</span>
                <span class="confirm-total-amount">${totalStr}</span>
              </div>
              <div class="bank-acc-display">
                <div class="bank-acc-row">
                  <span style="color: #64748b; font-weight: 600;">Bank Tujuan</span>
                  <strong style="color: #0f172a;">${paymentName}</strong>
                </div>
                <div class="bank-acc-row">
                  <span style="color: #64748b; font-weight: 600;">Nomor Rekening</span>
                  <div>
                    <span style="font-family: monospace; font-size: 15px; font-weight: 700; color: #244173;" id="bankAccNumber">7820192837</span>
                    <button type="button" class="btn-copy-acc" onclick="navigator.clipboard.writeText('7820192837'); alert('Nomor rekening berhasil disalin!');">Salin</button>
                  </div>
                </div>
                <div class="bank-acc-row" style="margin-bottom: 0;">
                  <span style="color: #64748b; font-weight: 600;">Atas Nama</span>
                  <strong style="color: #0f172a;">CV Maybeads Indonesia</strong>
                </div>
              </div>
            `;
          } else {
            // E-Wallet (GoPay, Dana, ShopeePay, etc.)
            instructionHTML = `
              <div class="confirm-total-box" style="margin-bottom: 12px;">
                <span class="confirm-total-label">Total Pembayaran:</span>
                <span class="confirm-total-amount">${totalStr}</span>
              </div>
              <div class="bank-acc-display">
                <div class="bank-acc-row">
                  <span style="color: #64748b; font-weight: 600;">E-Wallet</span>
                  <strong style="color: #0f172a;">${paymentName}</strong>
                </div>
                <div class="bank-acc-row">
                  <span style="color: #64748b; font-weight: 600;">Nomor Akun</span>
                  <div>
                    <span style="font-family: monospace; font-size: 15px; font-weight: 700; color: #244173;">0812-3456-7890</span>
                    <button type="button" class="btn-copy-acc" onclick="navigator.clipboard.writeText('081234567890'); alert('Nomor akun e-wallet berhasil disalin!');">Salin</button>
                  </div>
                </div>
                <div class="bank-acc-row" style="margin-bottom: 0;">
                  <span style="color: #64748b; font-weight: 600;">Atas Nama</span>
                  <strong style="color: #0f172a;">Maybeads Official</strong>
                </div>
              </div>
            `;
          }

          if (instructionContentArea) {
            instructionContentArea.innerHTML = instructionHTML;
          }

          // Bersihkan keranjang belanja setelah checkout
          try {
            localStorage.removeItem('maybeads_cart');
          } catch (e) {}

          if (paymentInstructionModal) {
            paymentInstructionModal.style.display = 'flex';
          }
        });
      }
    });
  </script>

  @auth
    @include('partials.user-logout-modal')
  @endauth
</body>
</html>

