"use client";

import { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";

type User = {
  id: number;
  username: string;
  email: string;
  created_at?: string;
};

type Sale = {
  id: number;
  action_type: string;
  item_name: string;
  amount: number;
  created_at: string;
};

type InventoryItem = {
  id: number;
  item_name: string;
  item_image: string;
  item_value: number;
  acquired_from: string;
  acquired_at: string;
};

export default function UserDetailsPage() {
  const params = useParams();
  const router = useRouter();
  const id = params?.id as string;

  const [activeTab, setActiveTab] = useState("info");
  const [user, setUser] = useState<User | null>(null);
  const [balance, setBalance] = useState<number>(0);
  const [sales, setSales] = useState<Sale[]>([]);
  const [inventory, setInventory] = useState<InventoryItem[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const [topUpAmount, setTopUpAmount] = useState("");
  const [deductAmount, setDeductAmount] = useState("");

  const [topUpMsg, setTopUpMsg] = useState("");
  const [deductMsg, setDeductMsg] = useState("");
  const [deleteMsg, setDeleteMsg] = useState("");
  const [confirmDelete, setConfirmDelete] = useState(false);

  const [checkingAuth, setCheckingAuth] = useState(true);

  useEffect(() => {
    if (typeof window === "undefined") return;

    const token = localStorage.getItem("admin");

    if (!token) {
      router.replace("/admin/login");
    } else {
      setCheckingAuth(false);
    }
  }, [router]);

  useEffect(() => {
    if (!id || checkingAuth) return;

    async function load() {
      try {
        const resUser = await fetch(
          `http://localhost/glimzyskins/backend/admin/getUserDetails.php?id=${id}`,
          { cache: "no-store" }
        );
        const userData = await resUser.json();

        if (!userData.success) {
          setError("Nie znaleziono użytkownika");
          setLoading(false);
          return;
        }

        setUser(userData.user);

        const resBalance = await fetch(
          `http://localhost/glimzyskins/backend/get-user-balance.php?user_id=${id}`,
          { cache: "no-store" }
        );
        const balanceData = await resBalance.json();
        setBalance(Number(balanceData.balance || 0));

        const resSales = await fetch(
          `http://localhost/glimzyskins/backend/admin/getUserSalesHistory.php?user_id=${id}`,
          { cache: "no-store" }
        );
        const salesData = await resSales.json();
        setSales(salesData.history || []);

        const resInv = await fetch(
          `http://localhost/glimzyskins/backend/get-inventory.php?user_id=${id}`,
          { cache: "no-store" }
        );
        const invData = await resInv.json();
        setInventory(invData.items || []);
      } catch {
        setError("Błąd połączenia z backendem");
      } finally {
        setLoading(false);
      }
    }

    load();
  }, [id, checkingAuth]);

  if (checkingAuth)
    return (
      <div className="flex justify-center items-center min-h-screen bg-black text-orange-500 text-lg font-medium">
        Sprawdzanie uprawnień...
      </div>
    );

  if (loading)
    return (
      <div className="flex justify-center items-center min-h-screen bg-black text-orange-500 text-lg font-medium">
        <div className="flex flex-col items-center gap-4">
          <div className="w-12 h-12 border-4 border-orange-500 border-t-transparent rounded-full animate-spin"></div>
          <div className="animate-pulse">Ładowanie danych użytkownika...</div>
        </div>
      </div>
    );

  if (error)
    return (
      <div className="flex flex-col justify-center items-center min-h-screen bg-black p-6">
        <div className="bg-white/5 p-8 rounded-2xl border border-orange-500/20 max-w-md w-full">
          <p className="text-white text-lg mb-6 text-center">{error}</p>
          <button
            onClick={() => router.push("/admin")}
            className="w-full px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl"
          >
            Powrót do panelu
          </button>
        </div>
      </div>
    );

  const tabs = [
    { id: "info", label: "Informacje" },
    { id: "sales", label: "Historia sprzedaży" },
    { id: "inventory", label: "Ekwipunek" },
    { id: "admin", label: "Akcje admina" },
  ];

  return (
    <div className="flex flex-col md:flex-row min-h-screen bg-black">
      <aside className="w-full md:w-72 bg-white/5 p-6 border-r border-orange-500/20">
        <h2 className="text-orange-500 text-2xl font-bold mb-6 text-center">Panel Admina</h2>

        <nav className="flex md:flex-col space-x-2 md:space-x-0 md:space-y-2 overflow-x-auto">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`px-4 py-2 rounded-lg text-left transition ${
                activeTab === tab.id ? "bg-orange-500 text-white" : "bg-white/5 text-white"
              }`}
            >
              {tab.label}
            </button>
          ))}
        </nav>

        <button
          onClick={() => router.push("/admin")}
          className="mt-10 w-full px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg"
        >
          Powrót
        </button>
      </aside>

      <main className="flex-1 p-6 text-white">
        {activeTab === "info" && (
          <div className="space-y-4">
            <h1 className="text-3xl font-bold text-orange-500">{user?.username}</h1>
            <p>Email: {user?.email}</p>
            <p>Data utworzenia: {user?.created_at}</p>
            <p className="text-xl mt-4">
              Saldo: <span className="text-orange-400">{balance.toFixed(2)} zł</span>
            </p>
          </div>
        )}

        {activeTab === "sales" && (
          <div className="space-y-4">
            {sales.length === 0 && <p>Brak historii.</p>}
            {sales.map((s) => (
              <div key={s.id} className="bg-white/5 p-4 rounded-lg border border-orange-500/20">
                <p>{s.action_type}</p>
                <p>{s.item_name}</p>
                <p>{s.amount} zł</p>
                <p>{s.created_at}</p>
              </div>
            ))}
          </div>
        )}

        {activeTab === "inventory" && (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {inventory.length === 0 && <p>Brak przedmiotów.</p>}
            {inventory.map((item) => (
              <div key={item.id} className="bg-white/5 p-4 rounded-lg border border-orange-500/20">
                <img src={item.item_image} className="w-20 h-20 object-contain mb-2" />
                <p>{item.item_name}</p>
                <p>Wartość: {item.item_value} zł</p>
                <p>Źródło: {item.acquired_from}</p>
                <p>{item.acquired_at}</p>
              </div>
            ))}
          </div>
        )}

        {activeTab === "admin" && (
          <div className="space-y-8 mt-4">
            
            <div className="bg-white/5 p-6 rounded-xl border border-orange-500/20 space-y-3">
              <h3 className="text-lg font-semibold">Doładuj saldo</h3>
              <input
                type="number"
                placeholder="Kwota"
                className="w-full p-3 rounded-lg bg-black/40 text-white border border-orange-500/30"
                value={topUpAmount}
                onChange={(e) => setTopUpAmount(e.target.value)}
              />
              <button
                onClick={async () => {
                  setTopUpMsg("");

                  const res = await fetch("http://localhost/glimzyskins/backend/add-balance.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                      user_id: id,
                      amount: parseFloat(topUpAmount),
                    }),
                  });

                  const data = await res.json();

                  if (data.success) {
                    setBalance(data.balance);
                    setTopUpMsg("Saldo zostało doładowane");
                  } else {
                    setTopUpMsg("Błąd: " + (data.error || "Nie udało się wykonać operacji"));
                  }

                  setTimeout(() => setTopUpMsg(""), 3000);
                }}
                className="w-full px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white rounded-xl"
              >
                Doładuj
              </button>

              {topUpMsg && <p className="text-center text-orange-400">{topUpMsg}</p>}
            </div>

            <div className="bg-white/5 p-6 rounded-xl border border-orange-500/20 space-y-3">
              <h3 className="text-lg font-semibold">Zmniejsz saldo</h3>
              <input
                type="number"
                placeholder="Kwota"
                className="w-full p-3 rounded-lg bg-black/40 text-white border border-orange-500/30"
                value={deductAmount}
                onChange={(e) => setDeductAmount(e.target.value)}
              />
              <button
                onClick={async () => {
                  setDeductMsg("");

                  const res = await fetch("http://localhost/glimzyskins/backend/deduct-balance.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                      user_id: id,
                      amount: parseFloat(deductAmount),
                    }),
                  });

                  const data = await res.json();

                  if (data.success) {
                    setBalance(data.balance);
                    setDeductMsg("Saldo zostało zmniejszone");
                  } else {
                    setDeductMsg("Błąd: " + (data.error || "Nie udało się wykonać operacji"));
                  }

                  setTimeout(() => setDeductMsg(""), 3000);
                }}
                className="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl"
              >
                Zmniejsz
              </button>

              {deductMsg && <p className="text-center text-red-400">{deductMsg}</p>}
            </div>

            <div className="bg-white/5 p-6 rounded-xl border border-red-500/30 space-y-4">
              <h3 className="text-lg font-semibold text-red-400">Usuń użytkownika</h3>

              {!confirmDelete && (
                <button
                  onClick={() => setConfirmDelete(true)}
                  className="w-full px-6 py-3 bg-red-700 hover:bg-red-800 text-white rounded-xl"
                >
                  Usuń użytkownika
                </button>
              )}

              {confirmDelete && (
                <div className="space-y-3">
                  <p className="text-red-300 text-center">Czy na pewno chcesz usunąć?</p>

                  <button
                    onClick={async () => {
                      setDeleteMsg("");

                      const formData = new FormData();
                      formData.append("id", id);

                      const res = await fetch(
                        "http://localhost/glimzyskins/backend/admin/deleteUser.php",
                        { method: "POST", body: formData }
                      );

                      const data = await res.json();

                      if (data.success) {
                        setDeleteMsg("Użytkownik został usunięty");
                        setTimeout(() => router.push("/admin"), 1500);
                      } else {
                        setDeleteMsg("Błąd: " + data.message);
                      }
                    }}
                    className="w-full px-6 py-3 bg-red-700 hover:bg-red-800 text-white rounded-xl"
                  >
                    Potwierdź usunięcie
                  </button>

                  <button
                    onClick={() => setConfirmDelete(false)}
                    className="w-full px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl"
                  >
                    Anuluj
                  </button>
                </div>
              )}

              {deleteMsg && <p className="text-center text-red-400">{deleteMsg}</p>}
            </div>
          </div>
        )}
      </main>
    </div>
  );
}