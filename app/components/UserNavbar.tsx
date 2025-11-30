"use client";

import Link from "next/link";
import { motion } from "framer-motion";

type UserNavbarProps = {
  active: "inventory" | "history" | "wallet";
  balance: number;
};

export default function UserNavbar({ active, balance }: UserNavbarProps) {
  return (
    <nav className="w-full bg-neutral-950 border-b border-neutral-800">
      <div className="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <div className="flex items-center gap-10">
          <Link href="/" className="text-neutral-400 hover:text-white transition font-semibold">
            ← Powrót do skrzynek
          </Link>

          <div className="flex gap-8 relative">
            <Link href="/inventory" className="relative">
              <button className="text-neutral-300 hover:text-white transition font-semibold">
                Ekwipunek
              </button>
              {active === "inventory" && (
                <motion.div
                  layoutId="underline"
                  className="absolute left-0 -bottom-1 h-0.5 bg-amber-400"
                  initial={{ width: 0 }}
                  animate={{ width: "100%" }}
                  transition={{ duration: 0.25 }}
                />
              )}
            </Link>

            <Link href="/history" className="relative">
              <button className="text-neutral-300 hover:text-white transition font-semibold">
                Historia
              </button>
              {active === "history" && (
                <motion.div
                  layoutId="underline"
                  className="absolute left-0 -bottom-1 h-0.5 bg-amber-400"
                  initial={{ width: 0 }}
                  animate={{ width: "100%" }}
                  transition={{ duration: 0.25 }}
                />
              )}
            </Link>

            <Link href="/wallet" className="relative">
              <button className="text-neutral-300 hover:text-white transition font-semibold">
                Portfel
              </button>
              {active === "wallet" && (
                <motion.div
                  layoutId="underline"
                  className="absolute left-0 -bottom-1 h-0.5 bg-amber-400"
                  initial={{ width: 0 }}
                  animate={{ width: "100%" }}
                  transition={{ duration: 0.25 }}
                />
              )}
            </Link>
          </div>
        </div>

        
        <div className="text-neutral-300 text-sm">
          Saldo:{" "}
          <span className="text-amber-400 font-bold">
            {Number(balance || 0).toFixed(2)} zł
          </span>
        </div>

      </div>
    </nav>
  );
}