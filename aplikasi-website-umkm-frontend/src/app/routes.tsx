import { createBrowserRouter } from "react-router";

import { Login } from "./pages/Login";

import { AdminLayout } from "./components/layouts/AdminLayout";
import { AdminDashboard } from "./pages/admin/Dashboard";
import { AdminProducts } from "./pages/admin/Products";
import { AdminInventory } from "./pages/admin/Inventory";
import { AdminEmployees } from "./pages/admin/Employees";
import { AdminReviews } from "./pages/admin/Reviews";

import { StaffLayout } from "./components/layouts/StaffLayout";
import { StaffOrders } from "./pages/staff/Orders";
import { StaffStock } from "./pages/staff/Stock";

import { CustomerLayout } from "./components/layouts/CustomerLayout";
import { CustomerCatalog } from "./pages/customer/Catalog";
import { CustomerCart } from "./pages/customer/Cart";
import { CustomerTrackOrder } from "./pages/customer/TrackOrder";
import { CustomerHistory } from "./pages/customer/History";

export const router = createBrowserRouter([
  {
    path: "/",
    Component: Login,
  },
  {
    path: "/admin",
    Component: AdminLayout,
    children: [
      { index: true, Component: AdminDashboard },
      { path: "products", Component: AdminProducts },
      { path: "inventory", Component: AdminInventory },
      { path: "employees", Component: AdminEmployees },
      { path: "reviews", Component: AdminReviews },
    ],
  },
  {
    path: "/staff",
    Component: StaffLayout,
    children: [
      { index: true, Component: StaffOrders },
      { path: "stock", Component: StaffStock },
    ],
  },
  {
    path: "/customer",
    Component: CustomerLayout,
    children: [
      { index: true, Component: CustomerCatalog },
      { path: "cart", Component: CustomerCart },
      { path: "track", Component: CustomerTrackOrder },
      { path: "history", Component: CustomerHistory },
    ],
  },
]);
