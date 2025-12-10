"use client";

import { useEffect, useState } from "react";
import Link from "next/link";
import { useRouter } from "next/navigation";

interface User {
  id: number;
  username: string;
  email?: string;
  balance?: number;
  created_at?: string;
}

export default function AdminUsersPage() {
  const [users, setUsers] = useState<User[]>([]);
  const [loading, setLoading] = useState(true);
  const router = useRouter();

  function logout() {
    localStorage.removeItem("admin");
    router.push("/admin/login");
  }

  useEffect(() => {
    const admin = localStorage.getItem("admin");
    if (!admin) {
      router.push("/admin/login");
      return;
    }

    fetch("http://localhost/glimzyskins/backend/admin/getUsers.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setUsers(data.users);
        }
      })
      .finally(() => setLoading(false));
  }, [router]);

  return (
    <div className="min-h-screen bg-black p-6 md:p-10">
      <div className="max-w-7xl mx-auto">

        <div className="flex justify-between items-center mb-8">
          <div>
            <h1 className="text-3xl md:text-4xl font-extrabold text-orange-500 mb-2">
              Użytkownicy
            </h1>
            <p className="text-gray-400">Zarządzaj użytkownikami systemu</p>
          </div>

          <button
            onClick={logout}
            className="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-semibold"
          >
            Wyloguj
          </button>
        </div>

        {loading ? (
          <div className="bg-white/5 p-12 rounded-xl shadow-md border border-orange-500/30 text-center">
            <div className="animate-pulse text-orange-400 text-lg font-medium">
              Ładowanie użytkowników...
            </div>
          </div>
        ) : (
          <div className="bg-white/5 rounded-xl shadow-lg border border-orange-500/20 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-left">
                <thead className="bg-gradient-to-r from-orange-600 to-orange-500">
                  <tr>
                    <th className="px-6 py-4 text-white font-bold text-sm uppercase tracking-wider">ID</th>
                    <th className="px-6 py-4 text-white font-bold text-sm uppercase tracking-wider">Nick</th>
                    <th className="px-6 py-4 text-white font-bold text-sm uppercase tracking-wider">Email</th>
                    <th className="px-6 py-4 text-white font-bold text-sm uppercase tracking-wider">Saldo</th>
                    <th className="px-6 py-4 text-white font-bold text-sm uppercase tracking-wider text-center">Akcje</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-white/10">
                  {users.length === 0 ? (
                    <tr>
                      <td colSpan={5} className="px-6 py-12 text-center text-gray-500">
                        Brak użytkowników
                      </td>
                    </tr>
                  ) : (
                    users.map((u) => (
                      <tr key={u.id} className="hover:bg-white/10 transition-colors duration-150">
                        <td className="px-6 py-4 whitespace-nowrap">
                          <span className="text-white font-medium">#{u.id}</span>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <span className="text-white font-medium">{u.username}</span>
                        </td>
                        <td className="px-6 py-4">
                          <span className="text-gray-300">{u.email || "—"}</span>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap">
                          <span className="text-orange-400 font-bold text-lg">
                            {Number(u.balance ?? 0).toFixed(2)} zł
                          </span>
                        </td>
                        <td className="px-6 py-4 whitespace-nowrap text-center">
                          <Link
                            href={`/admin/users/${u.id}`}
                            className="inline-block px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-medium transition-all duration-150 transform hover:scale-105"
                          >
                            Szczegóły
                          </Link>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>

            {users.length > 0 && (
              <div className="bg-white/5 px-6 py-4 border-t border-orange-500/20">
                <p className="text-sm text-gray-400">
                  Łącznie: <span className="font-semibold text-orange-400">{users.length}</span> użytkowników
                </p>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
}