"use client";

import { useEffect, useState } from "react";
import { useRouter } from "next/navigation";
import UserNavbar from "../components/UserNavbar"; 

interface User {
  id: number;
  username: string;
  email: string;
  balance: number;
}

export default function WalletPage() {
  const router = useRouter();
  const [user, setUser] = useState<User | null>(null);
  const [amount, setAmount] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    const stored = localStorage.getItem("user");
    if (!stored) {
      router.push("/");
      return;
    }
    const parsed: User = JSON.parse(stored);
    setUser(parsed);
  }, [router]);

  async function handleAddBalance() {
    if (!user) return;

    const amt = parseFloat(amount);
    if (isNaN(amt) || amt <= 0) {
      setError("Podaj poprawną kwotę do doładowania");
      return;
    }

    setIsLoading(true);
    setError(null);
    setSuccess(null);

    try {
      const res = await fetch("http://localhost/glimzyskins/backend/add-balance.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: user.id, amount: amt }),
      });

      const data = await res.json();

      if (!data.success) {
        setError(data.error || "Nie udało się doładować salda");
      } else {
        const updatedUser = { ...user, balance: data.balance };
        setUser(updatedUser);
        localStorage.setItem("user", JSON.stringify(updatedUser));
        setSuccess(`Doładowano ${amt.toFixed(2)} zł`);
        setAmount("");
      }
    } catch (e: unknown) {
      if (e instanceof Error) setError("Błąd sieci: " + e.message);
      else setError("Nie udało się połączyć z serwerem");
    } finally {
      setIsLoading(false);
    }
  }

  if (!user) return null;

  return (
    <div className="min-h-screen bg-neutral-900 text-white">
      <UserNavbar active="wallet" balance={user.balance} />

      <div className="max-w-4xl mx-auto p-8">
        <h1 className="text-3xl font-bold mb-6">Portfel — Doładowanie</h1>

        <div className="bg-neutral-800 rounded-2xl shadow-2xl p-8 flex flex-col gap-6">
          <div className="bg-neutral-700 p-6 rounded-xl shadow-inner flex flex-col items-center">
            <p className="text-gray-400 text-sm mb-2">Saldo konta</p>
            <p className="text-3xl font-semibold text-amber-400">
              {user.balance !== undefined ? user.balance.toFixed(2) : "0.00"} zł
            </p>
          </div>

          <div className="flex flex-col gap-4 w-full max-w-sm mx-auto">
            <input
              type="number"
              placeholder="Kwota do doładowania"
              value={amount}
              onChange={(e) => setAmount(e.target.value)}
              className="p-4 rounded-xl text-amber-300 text-lg font-medium outline-none focus:ring-2 focus:ring-amber-400"
            />
            <button
              onClick={handleAddBalance}
              disabled={isLoading}
              className={`p-4 rounded-xl font-semibold text-white transition-colors ${
                isLoading ? "bg-amber-600/60 cursor-not-allowed" : "bg-amber-500 hover:bg-amber-600"
              }`}
            >
              {isLoading ? "Doładowywanie..." : "Doładuj konto"}
            </button>
          </div>

          {error && <p className="mt-4 text-red-500 font-medium text-center">{error}</p>}
          {success && <p className="mt-4 text-green-400 font-medium text-center">{success}</p>}
        </div>
      </div>
    </div>
  );
}