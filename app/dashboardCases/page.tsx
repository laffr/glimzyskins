"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import Link from "next/link";
import CasesList from "../components/CasesList";
import { Menu, X, User, Package, History, LogOut, CreditCard } from "lucide-react";
import { motion, AnimatePresence } from "framer-motion";

export default function DashboardCases() {
  const router = useRouter();
  const [open, setOpen] = useState(false);

  useEffect(() => {
    const stored = localStorage.getItem("user");
    if (!stored) router.push("/");
  }, [router]);

  function logout() {
    localStorage.removeItem("user");
    router.push("/");
  }

  return (
    <div className="min-h-screen bg-neutral-950 text-white relative">

      
      <nav className="w-full p-4 bg-neutral-900 border-b border-neutral-800 shadow-[0_5px_10px_-3px_rgba(0,0,0,0.5)] flex justify-end">

        <button
          onClick={() => setOpen(!open)}
          className="flex items-center gap-2 px-4 py-2 rounded-lg bg-neutral-800 
          hover:bg-neutral-700 transition shadow-sm border border-neutral-700"
        >
          <User className="w-5 h-5 text-amber-400" />
          <span className="font-semibold">Profil</span>
          {open ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
        </button>

        <AnimatePresence>
          {open && (
            <motion.div
              initial={{ opacity: 0, y: -10 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0, y: -10 }}
              transition={{ duration: 0.2 }}
              className="absolute top-16 right-4 w-48 bg-neutral-900 border 
              border-neutral-800 rounded-xl shadow-xl overflow-hidden z-50"
            >
              <div className="flex flex-col">

                <Link
                  href="/inventory"
                  className="flex items-center gap-3 px-4 py-3 hover:bg-neutral-800 transition"
                >
                  <Package className="w-5 h-5 text-amber-400" />
                  Ekwipunek
                </Link>

                <Link
                  href="/history"
                  className="flex items-center gap-3 px-4 py-3 hover:bg-neutral-800 transition"
                >
                  <History className="w-5 h-5 text-amber-400" />
                  Historia
                </Link>

                <Link
                  href="/wallet"
                  className="flex items-center gap-3 px-4 py-3 hover:bg-neutral-800 transition"
                >
                  <CreditCard className="w-5 h-5 text-amber-400" />
                  Portfel
                </Link>

                <button
                  onClick={logout}
                  className="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-900/20 transition text-left"
                >
                  <LogOut className="w-5 h-5" />
                  Wyloguj
                </button>

              </div>
            </motion.div>
          )}
        </AnimatePresence>
      </nav>

      <div className="p-6">
        <CasesList />
      </div>

    </div>
  );
}